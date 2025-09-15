<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        /**
         *  * date            (date/datetime)
            * description     (string / text) -- e.g., "Sale Invoice #123"
            * reference_id    (nullable, polymorphic) -- link to sales, purchases, etc.
            * reference_type  (nullable, e.g., 'Sale', 'Purchase', 'Expense')
            * branch_id       (nullable FK -> branches.id)
            * total_amount    (decimal 15,2)

         */
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->text('description')->nullable();
            $table->string('reference_type')->nullable();
            $table->unsignedBigInteger('reference_id')->nullable();
            $table->unsignedBigInteger('branch_id')->nullable();
            $table->decimal('total_amount',15,2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
