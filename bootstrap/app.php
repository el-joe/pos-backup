<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->validateCsrfTokens(except : ['livewire/*', 'webhooks/*']);
        // $middleware->web([
        //     \Stancl\Tenancy\Middleware\InitializeTenancyByDomain::class,
        //     \Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains::class,
        // ]);
        $middleware->alias([
            'tenant.system' => \App\Http\Middleware\Tenant\CheckTenantSystem::class,
            'track.pageview' => \App\Http\Middleware\TrackPageView::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (\Illuminate\Validation\ValidationException $e, $request) {
            if ($request->is('api/v1/*')) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage(),
                    'errors' => $e->errors(),
                ], 422);
            }
        });

        $exceptions->render(function (\Illuminate\Auth\AuthenticationException $e, $request) {
            if ($request->is('api/v1/*')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized',
                    'code' => 401,
                ], 401);
            }
        });

        $exceptions->render(function (\Symfony\Component\HttpKernel\Exception\NotFoundHttpException $e, $request) {
            if ($request->is('api/v1/*')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Not Found',
                    'code' => 404,
                ], 404);
            }
        });

        $exceptions->render(function (\Throwable $e, $request) {
            if ($request->is('api/v1/*') && !($e instanceof \Illuminate\Http\Exceptions\HttpResponseException)) {
                $status = method_exists($e, 'getStatusCode') ? $e->getStatusCode() : 500;
                if ($status >= 400 && $status < 600) {
                    return response()->json([
                        'success' => false,
                        'message' => $e->getMessage() ?: 'Server Error',
                        'code' => $status,
                    ], $status);
                }
            }
        });
    })->create();
