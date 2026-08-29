<?php

namespace App\Services\EInvoice;

use App\Models\Country;
use App\Models\Tenant\Sale;
use BaconQrCode\Renderer\Image\ImagickImageBackEnd;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;
use Illuminate\Support\Facades\Cache;

class EInvoiceService
{
    public static function getStandard(string $countryCode): array
    {
        return config('einvoice.'.strtoupper($countryCode), config('einvoice.default'));
    }

    public static function getCountryCode(Sale $sale): string
    {
        $countryId = tenant('country_id');

        if (! $countryId) {
            return 'default';
        }

        $code = Country::find($countryId)?->code;

        return $code ? strtoupper($code) : 'default';
    }

    public static function shouldShowQr(Sale $sale): bool
    {
        return (bool) self::getStandard(self::getCountryCode($sale))['qr_required'] ?? false;
    }

    public static function shouldShowVatLine(Sale $sale): bool
    {
        return (bool) self::getStandard(self::getCountryCode($sale))['vat_required'] ?? false;
    }

    public static function generateQrCode(Sale $sale): string
    {
        return Cache::remember('sale_qr_'.$sale->id, 86400, function () use ($sale) {
            $tlv = self::buildZatcaTlv($sale);

            return self::renderQrPng(base64_encode($tlv));
        });
    }

    protected static function buildZatcaTlv(Sale $sale): string
    {
        $sellerName = (string) tenant('name');
        $vatNumber = (string) tenant('tax_number');
        $invoiceDate = optional($sale->created_at)->toIso8601String();
        $totalWithVat = (string) $sale->grand_total_amount;
        $vatAmount = (string) $sale->tax_amount;

        $tags = [
            1 => $sellerName,
            2 => $vatNumber,
            3 => $invoiceDate,
            4 => $totalWithVat,
            5 => $vatAmount,
        ];

        $tlv = '';
        foreach ($tags as $tag => $value) {
            $value = (string) $value;
            $tlv .= pack('C', $tag).pack('C', strlen($value)).$value;
        }

        return $tlv;
    }

    protected static function renderQrPng(string $data): string
    {
        if (class_exists(Writer::class) && extension_loaded('imagick')) {
            $renderer = new ImageRenderer(
                new RendererStyle(200),
                new ImagickImageBackEnd()
            );

            $writer = new Writer($renderer);
            $png = $writer->writeString($data);

            return base64_encode($png);
        }

        return self::fallbackQr($data);
    }

    protected static function fallbackQr(string $data): string
    {
        $url = 'https://api.qrserver.com/v1/create-qr-code/?size=200x200&data='.urlencode($data);
        $png = @file_get_contents($url);

        return $png ? base64_encode($png) : '';
    }
}
