<?php

namespace Tests\Feature\Tenant;

use App\Helpers\SaleHelper;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class GrandTotalSqlMatchesPhpTest extends TestCase
{
    private const TEST_DATABASE = 'test_grand_total_sql_matches_php';
    private const CONNECTION = 'testing_grand_total';

    protected function setUp(): void
    {
        parent::setUp();

        DB::connection('mysql')->statement('CREATE DATABASE IF NOT EXISTS `'.self::TEST_DATABASE.'`');

        config(['database.connections.'.self::CONNECTION => array_merge(
            config('database.connections.mysql'),
            ['database' => self::TEST_DATABASE]
        )]);
        DB::purge(self::CONNECTION);

        foreach (['sale_items', 'sales'] as $table) {
            Schema::connection(self::CONNECTION)->dropIfExists($table);
        }

        Schema::connection(self::CONNECTION)->create('sales', function (Blueprint $table) {
            $table->id();
            $table->string('discount_type', 20)->nullable();
            $table->decimal('discount_value', 12, 2)->default(0);
            $table->decimal('max_discount_amount', 15, 2)->nullable();
            $table->decimal('sales_threshold', 15, 2)->nullable();
            $table->decimal('tax_percentage', 12, 2)->default(0);
            $table->decimal('paid_amount', 12, 2)->default(0);
            $table->timestamps();
        });

        Schema::connection(self::CONNECTION)->create('sale_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sale_id')->index();
            $table->decimal('qty', 15, 3)->default(0);
            $table->decimal('refunded_qty', 15, 3)->default(0);
            $table->decimal('sell_price', 15, 4)->default(0);
            $table->boolean('taxable')->default(0);
            $table->timestamps();
        });
    }

    private function matrix(): array
    {
        return [
            'no_discount' => [
                'sale' => ['discount_type' => null, 'discount_value' => 0, 'max_discount_amount' => 0, 'sales_threshold' => null, 'tax_percentage' => 0],
                'items' => [['qty' => 2, 'sell_price' => 100, 'taxable' => 0, 'refunded_qty' => 0]],
            ],
            'percentage_under_cap' => [
                'sale' => ['discount_type' => 'percentage', 'discount_value' => 10, 'max_discount_amount' => 100, 'sales_threshold' => null, 'tax_percentage' => 0],
                'items' => [['qty' => 2, 'sell_price' => 100, 'taxable' => 0, 'refunded_qty' => 0]],
            ],
            'percentage_over_cap' => [
                'sale' => ['discount_type' => 'percentage', 'discount_value' => 50, 'max_discount_amount' => 30, 'sales_threshold' => null, 'tax_percentage' => 0],
                'items' => [['qty' => 2, 'sell_price' => 100, 'taxable' => 0, 'refunded_qty' => 0]],
            ],
            'fixed_under_threshold' => [
                'sale' => ['discount_type' => 'fixed', 'discount_value' => 20, 'max_discount_amount' => 0, 'sales_threshold' => 100, 'tax_percentage' => 0],
                'items' => [['qty' => 1, 'sell_price' => 50, 'taxable' => 0, 'refunded_qty' => 0]],
            ],
            'fixed_over_threshold' => [
                'sale' => ['discount_type' => 'fixed', 'discount_value' => 20, 'max_discount_amount' => 0, 'sales_threshold' => 100, 'tax_percentage' => 0],
                'items' => [['qty' => 3, 'sell_price' => 100, 'taxable' => 0, 'refunded_qty' => 0]],
            ],
            'fixed_exceeding_subtotal' => [
                'sale' => ['discount_type' => 'fixed', 'discount_value' => 500, 'max_discount_amount' => 0, 'sales_threshold' => null, 'tax_percentage' => 0],
                'items' => [['qty' => 1, 'sell_price' => 50, 'taxable' => 0, 'refunded_qty' => 0]],
            ],
            'mixed_taxable_basket' => [
                'sale' => ['discount_type' => 'percentage', 'discount_value' => 20, 'max_discount_amount' => 0, 'sales_threshold' => null, 'tax_percentage' => 10],
                'items' => [
                    ['qty' => 1, 'sell_price' => 100, 'taxable' => 1, 'refunded_qty' => 0],
                    ['qty' => 1, 'sell_price' => 100, 'taxable' => 0, 'refunded_qty' => 0],
                ],
            ],
            'fully_refunded_line' => [
                'sale' => ['discount_type' => 'fixed', 'discount_value' => 10, 'max_discount_amount' => 0, 'sales_threshold' => null, 'tax_percentage' => 5],
                'items' => [['qty' => 5, 'sell_price' => 100, 'taxable' => 1, 'refunded_qty' => 5]],
            ],
        ];
    }

    public function test_sql_grand_total_matches_php_across_matrix(): void
    {
        $connection = DB::connection(self::CONNECTION);

        foreach ($this->matrix() as $case => $definition) {
            $saleId = $connection->table('sales')->insertGetId([
                ...$definition['sale'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            foreach ($definition['items'] as $item) {
                $connection->table('sale_items')->insert([
                    'sale_id' => $saleId,
                    ...$item,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        // The CTE query returns all sales in one shot; run it once and index by sale_id.
        $rows = collect($connection->select(SaleHelper::getGrandTotalQuery()))->keyBy('sale_id');

        $saleIds = $connection->table('sales')->pluck('id', 'id');
        $index = 0;
        foreach ($this->matrix() as $case => $definition) {
            $saleId = array_values($saleIds->all())[$index] ?? null;
            $index++;

            $row = $rows->get($saleId);
            $this->assertNotNull($row, "SQL result missing for case [{$case}]");

            $items = collect($definition['items'])->map(fn($i) => [
                'qty' => $i['qty'],
                'sell_price' => $i['sell_price'],
                'taxable' => $i['taxable'],
                'refunded_qty' => $i['refunded_qty'],
            ])->toArray();

            $sale = $definition['sale'];

            $phpDiscount = SaleHelper::discountAmount($items, $sale['discount_type'], $sale['discount_value'], $sale['max_discount_amount'], $sale['sales_threshold']);
            $phpTax = SaleHelper::taxAmount($items, $sale['discount_type'], $sale['discount_value'], $sale['tax_percentage'], $sale['max_discount_amount'], $sale['sales_threshold']);
            $phpGrandTotal = SaleHelper::grandTotal($items, $sale['discount_type'], $sale['discount_value'], $sale['tax_percentage'], $sale['max_discount_amount'], $sale['sales_threshold']);

            $this->assertEquals($phpDiscount, (float) $row->discount, "Discount mismatch for case [{$case}]");
            $this->assertEquals($phpTax, (float) $row->tax, "Tax mismatch for case [{$case}]");
            $this->assertEquals($phpGrandTotal, (float) $row->grand_total, "Grand total mismatch for case [{$case}]");
        }
    }
}
