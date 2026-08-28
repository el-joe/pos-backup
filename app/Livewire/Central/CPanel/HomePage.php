<?php

namespace App\Livewire\Central\CPanel;

use App\Models\Blog;
use App\Models\Contact;
use App\Models\Country;
use App\Models\Currency;
use App\Models\Faq;
use App\Models\Partner;
use App\Models\PartnerCommission;
use App\Models\RegisterRequest;
use App\Models\Subscription;
use App\Models\Tenant;
use App\Services\AnalyticsService;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Stevebauman\Location\Facades\Location;

#[Layout('layouts.cpanel')]
class HomePage extends Component
{
    public string $trafficPeriod = '30';

    public function setTrafficPeriod(string $days): void
    {
        $this->trafficPeriod = $days;
    }

    public function render()
    {
        $now = now();
        $analytics = app(AnalyticsService::class);

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
            'contacts_unread' => Contact::unread()->count(),
            'new_tenants_this_month' => $analytics->getNewTenantsThisMonth(),
            'revenue_this_month' => $analytics->getRevenueThisMonth(),
            'revenue_last_month' => $analytics->getRevenueLastMonth(),
            'mrr' => $analytics->getMRR(),
            'churn_rate' => $analytics->getChurnRate(),
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
            ->selectRaw('country_id, COUNT(*) as total')
            ->whereNotNull('country_id')
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

        $periodDays = (int) $this->trafficPeriod;
        $traffic = $analytics->getTrafficSummary($periodDays);
        $topPages = $analytics->getTopPages($periodDays);
        $trafficByCountry = $analytics->getTrafficByCountry($periodDays);
        $revenueByPlan = $analytics->getSubscriptionsByPlan();
        $expiringSoon = $analytics->getExpiringSubscriptions(7);

        return view('livewire.central.cpanel.home-page', compact(
            'stats',
            'paidAmountsByCurrency',
            'tenantsMapData',
            'tenantsByCountry',
            'traffic',
            'topPages',
            'trafficByCountry',
            'revenueByPlan',
            'expiringSoon'
        ));
    }
}
