<?php

namespace App\Livewire\Central\CPanel;

use App\Models\Blog;
use App\Models\Country;
use App\Models\Currency;
use App\Models\Faq;
use App\Models\Partner;
use App\Models\PartnerCommission;
use App\Models\RegisterRequest;
use App\Models\Subscription;
use App\Models\Tenant;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.cpanel')]
class HomePage extends Component
{
    public function render()
    {
        $now = now();

        $stats = [
            'pending_register_requests' => RegisterRequest::where('status', 'pending')->count(),
            'unread_register_requests' => RegisterRequest::whereNull('read_at')->count(),
            'tenants' => Tenant::count(),
            'blogs' => Blog::count(),
            'faqs' => Faq::count(),
            'partners' => Partner::count(),
            'partner_commissions' => PartnerCommission::count(),
            'partner_commissions_pending' => PartnerCommission::whereNull('collected_at')->count(),
            'subscriptions_all' => Subscription::count(),
            'subscriptions_paid' => Subscription::where('status', 'paid')->count(),
            'subscriptions_expiring_soon' => Subscription::where('status', 'paid')
                ->whereBetween('end_date', [$now, (clone $now)->addDays(3)])
                ->count(),
        ];

        $paidAmounts = Subscription::query()
            ->where('status', 'paid')
            ->selectRaw('currency_id, SUM(price) as total')
            ->groupBy('currency_id')
            ->get();

        $currencies = Currency::query()
            ->whereIn('id', $paidAmounts->pluck('currency_id')->filter()->all())
            ->get()
            ->keyBy('id');

        $paidAmountsByCurrency = $paidAmounts
            ->map(function ($row) use ($currencies) {
                $currency = $row->currency_id ? ($currencies[$row->currency_id] ?? null) : null;

                return [
                    'currency_id' => $row->currency_id,
                    'code' => $currency?->code ?? '-',
                    'symbol' => $currency?->symbol ?? '',
                    'total' => (float) ($row->total ?? 0),
                ];
            })
            ->values();

        $tenantCountryCounts = Tenant::query()
            ->selectRaw("JSON_UNQUOTE(JSON_EXTRACT(data, '$.country_id')) as country_id, COUNT(*) as total")
            ->whereRaw("JSON_EXTRACT(data, '$.country_id') IS NOT NULL")
            ->groupBy('country_id')
            ->get();

        $countryIds = $tenantCountryCounts
            ->pluck('country_id')
            ->filter(fn ($id) => filled($id))
            ->unique()
            ->values()
            ->all();

        $countries = Country::query()
            ->whereIn('id', $countryIds)
            ->get()
            ->keyBy('id');

        $tenantsMapData = [];
        $tenantsByCountry = [];

        foreach ($tenantCountryCounts as $row) {
            $countryId = (string) ($row->country_id ?? '');
            if ($countryId === '') {
                continue;
            }

            $country = $countries[$countryId] ?? null;
            $code = strtoupper((string) ($country?->code ?? ''));
            if ($code === '') {
                continue;
            }

            $total = (int) ($row->total ?? 0);
            $tenantsMapData[$code] = $total;

            $tenantsByCountry[] = [
                'country' => $country?->name ?? $code,
                'code' => $code,
                'total' => $total,
            ];
        }

        usort($tenantsByCountry, fn ($a, $b) => ($b['total'] ?? 0) <=> ($a['total'] ?? 0));
        $tenantsByCountry = array_slice($tenantsByCountry, 0, 10);

        return view('livewire.central.cpanel.home-page', compact('stats', 'paidAmountsByCurrency', 'tenantsMapData', 'tenantsByCountry'));
    }
}
