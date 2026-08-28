<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Stevebauman\Location\Facades\Location;
use Throwable;

class TrackPageViewJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 1;

    public function __construct(
        public string $path,
        public ?string $referrer,
        public ?string $userAgent,
        public ?string $ip,
        public ?string $sessionId,
    ) {}

    public function handle(): void
    {
        try {
            $countryCode = $this->ip ? Location::get($this->ip)?->countryCode : null;

            DB::connection('central')->table('page_views')->insert([
                'path' => mb_substr($this->path, 0, 500),
                'referrer' => $this->referrer ? mb_substr($this->referrer, 0, 500) : null,
                'user_agent' => $this->userAgent ? mb_substr($this->userAgent, 0, 500) : null,
                'ip' => $this->ip,
                'country_code' => $countryCode,
                'is_bot' => false,
                'session_id' => $this->sessionId,
                'created_at' => now(),
            ]);
        } catch (Throwable $e) {
            Log::warning('Failed to record page view: ' . $e->getMessage());
        }
    }
}
