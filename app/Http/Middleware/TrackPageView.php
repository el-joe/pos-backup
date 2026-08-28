<?php

namespace App\Http\Middleware;

use App\Jobs\TrackPageViewJob;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TrackPageView
{
    protected const BOT_PATTERNS = [
        'bot', 'crawl', 'spider', 'slurp', 'facebookexternalhit',
        'googlebot', 'bingbot', 'yandex', 'baiduspider', 'duckduckbot',
        'ahrefsbot', 'semrushbot', 'mj12bot', 'petalbot', 'applebot',
    ];

    public function handle(Request $request, Closure $next): Response
    {

        if ($request->isMethod('GET') && ! $request->is('cpanel*') && ! $request->is('api/*')) {
            $this->track($request);
        }

        return $next($request);
    }

    protected function track(Request $request): void
    {
        $ip = $request->ip();

        if ($this->isPrivateIp($ip)) {
            return;
        }

        $userAgent = (string) $request->userAgent();

        if ($this->isBot($userAgent)) {
            return;
        }

        TrackPageViewJob::dispatch(
            path: $request->path(),
            referrer: $request->headers->get('referer'),
            userAgent: $userAgent,
            ip: $ip,
            sessionId: $request->hasSession() ? $request->session()->getId() : null,
            countryCode: 'us',
        );
    }

    protected function isBot(string $userAgent): bool
    {
        $userAgent = strtolower($userAgent);

        foreach (self::BOT_PATTERNS as $pattern) {
            if (str_contains($userAgent, $pattern)) {
                return true;
            }
        }

        return false;
    }

    protected function isPrivateIp(?string $ip): bool
    {
        if (! $ip) {
            return true;
        }

        if ($ip === '127.0.0.1' || $ip === '::1') {
            return true;
        }

        return ! filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE);
    }
}
