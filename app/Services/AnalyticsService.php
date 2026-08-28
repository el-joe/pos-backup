<?php

namespace App\Services;

use App\Models\Currency;
use App\Models\Subscription;
use App\Models\Tenant;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class AnalyticsService
{
    public function getTrafficSummary(int $days = 30): array
    {
        $since = now()->subDays($days)->startOfDay();

        $totalViews = DB::connection('central')->table('page_views')
            ->where('created_at', '>=', $since)
            ->count();

        $uniqueSessions = DB::connection('central')->table('page_views')
            ->where('created_at', '>=', $since)
            ->whereNotNull('session_id')
            ->distinct('session_id')
            ->count('session_id');

        $viewsByDayRaw = DB::connection('central')->table('page_views')
            ->selectRaw('DATE(created_at) as day, COUNT(*) as total')
            ->where('created_at', '>=', $since)
            ->groupBy('day')
            ->orderBy('day')
            ->get()
            ->keyBy('day');

        $viewsByDay = [];
        $cursor = $since->copy();
        $today = now()->startOfDay();
        while ($cursor->lte($today)) {
            $key = $cursor->format('Y-m-d');
            $viewsByDay[] = [
                'date' => $key,
                'total' => (int) ($viewsByDayRaw[$key]->total ?? 0),
            ];
            $cursor->addDay();
        }

        $sessionViewCounts = DB::connection('central')->table('page_views')
            ->selectRaw('session_id, COUNT(*) as total')
            ->where('created_at', '>=', $since)
            ->whereNotNull('session_id')
            ->groupBy('session_id')
            ->pluck('total');

        $singleViewSessions = $sessionViewCounts->filter(fn ($count) => $count == 1)->count();
        $bounceRateApprox = $uniqueSessions > 0
            ? round(($singleViewSessions / $uniqueSessions) * 100, 2)
            : 0.0;

        return [
            'total_views' => $totalViews,
            'unique_sessions' => $uniqueSessions,
            'top_pages' => $this->getTopPages($days, 5),
            'views_by_day' => $viewsByDay,
            'bounce_rate_approx' => $bounceRateApprox,
        ];
    }

    public function getTopPages(int $days = 30, int $limit = 10): array
    {
        $since = now()->subDays($days)->startOfDay();

        $rows = DB::connection('central')->table('page_views')
            ->selectRaw('path, COUNT(*) as total')
            ->where('created_at', '>=', $since)
            ->groupBy('path')
            ->orderByDesc('total')
            ->limit($limit)
            ->get();

        $totalViews = DB::connection('central')->table('page_views')
            ->where('created_at', '>=', $since)
            ->count();

        return $rows->map(function ($row) use ($totalViews) {
            return [
                'path' => $row->path,
                'views' => (int) $row->total,
                'percentage' => $totalViews > 0 ? round(($row->total / $totalViews) * 100, 2) : 0.0,
            ];
        })->values()->all();
    }

    public function getTrafficByCountry(int $days = 30): array
    {
        $since = now()->subDays($days)->startOfDay();

        return DB::connection('central')->table('page_views')
            ->selectRaw('country_code, COUNT(*) as total')
            ->where('created_at', '>=', $since)
            ->whereNotNull('country_code')
            ->groupBy('country_code')
            ->orderByDesc('total')
            ->get()
            ->map(fn ($row) => [
                'country_code' => $row->country_code,
                'views' => (int) $row->total,
            ])
            ->values()
            ->all();
    }

    public function getNewTenantsThisMonth(): int
    {
        return Tenant::where('created_at', '>=', now()->startOfMonth())->count();
    }

    public function getRevenueThisMonth(): float
    {
        return (float) Subscription::where('status', 'paid')
            ->where('created_at', '>=', now()->startOfMonth())
            ->sum('price');
    }

    public function getRevenueLastMonth(): float
    {
        return (float) Subscription::where('status', 'paid')
            ->whereBetween('created_at', [
                now()->subMonthNoOverflow()->startOfMonth(),
                now()->subMonthNoOverflow()->endOfMonth(),
            ])
            ->sum('price');
    }

    public function getMRR(): float
    {
        $now = now();

        return (float) Subscription::where('status', 'paid')
            ->where('start_date', '<=', $now)
            ->where(function ($query) use ($now) {
                $query->whereNull('end_date')->orWhere('end_date', '>=', $now);
            })
            ->get()
            ->sum(function (Subscription $subscription) {
                return $subscription->billing_cycle === 'yearly'
                    ? $subscription->price / 12
                    : $subscription->price;
            });
    }

    public function getChurnRate(): float
    {
        $lastMonthStart = now()->subMonthNoOverflow()->startOfMonth();
        $lastMonthEnd = now()->subMonthNoOverflow()->endOfMonth();

        $totalLastMonth = Subscription::where('created_at', '<=', $lastMonthEnd)->count();

        if ($totalLastMonth === 0) {
            return 0.0;
        }

        $cancelledLastMonth = Subscription::where('status', 'cancelled')
            ->whereBetween('updated_at', [$lastMonthStart, $lastMonthEnd])
            ->count();

        return round(($cancelledLastMonth / $totalLastMonth) * 100, 2);
    }

    public function getSubscriptionsByPlan(): array
    {
        return Subscription::query()
            ->selectRaw('plan_id, COUNT(*) as count, SUM(CASE WHEN status = "paid" THEN price ELSE 0 END) as revenue')
            ->with('plan:id,name,name_en,name_ar')
            ->groupBy('plan_id')
            ->get()
            ->map(fn ($row) => [
                'plan_name' => $row->plan?->name ?? $row->plan?->name_en ?? '-',
                'count' => (int) $row->count,
                'revenue' => (float) $row->revenue,
            ])
            ->values()
            ->all();
    }

    public function getExpiringSubscriptions(int $days = 7): Collection
    {
        $now = now();

        return Subscription::where('status', 'paid')
            ->whereBetween('end_date', [$now, (clone $now)->addDays($days)])
            ->with(['tenant:id,name,email', 'plan:id,name,name_en,name_ar'])
            ->orderBy('end_date')
            ->get();
    }
}
