<?php

namespace App\Http\Controllers\Api\Tenant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

abstract class ApiController extends Controller
{
    public function __construct(Request $request)
    {
        // Respect Accept-Language for API responses (ar/en) without relying on session.
        $locale = $this->resolveLocale($request);
        if ($locale) {
            app()->setLocale($locale);
        }

        if ($this->permission() !== '' && !adminCan($this->permission())) {
            throw new \Illuminate\Http\Exceptions\HttpResponseException(response()->json([
                'success' => false,
                'message' => 'Unauthorized',
                'code' => 403,
            ], HttpResponse::HTTP_FORBIDDEN));
        }
    }

    /**
     * Permission key(s) (comma separated) required to access this controller's actions.
     * Override in child controllers.
     */
    protected function permission(): string
    {
        return '';
    }

    protected function resolveLocale(Request $request): ?string
    {
        $header = $request->header('Accept-Language');
        if (!$header) {
            return null;
        }

        $lang = strtolower(substr(trim(explode(',', $header)[0]), 0, 2));

        return in_array($lang, ['ar', 'en']) ? $lang : null;
    }

    protected function perPage(Request $request): int
    {
        $perPage = (int) $request->query('per_page', 20);
        if ($perPage < 1) {
            $perPage = 20;
        }

        return min($perPage, 100);
    }

    protected function success($data = null, int $status = 200, array $meta = [])
    {
        $payload = ['success' => true, 'data' => $data];
        if (!empty($meta)) {
            $payload['meta'] = $meta;
        }

        return response()->json($payload, $status);
    }

    protected function paginated(\Illuminate\Contracts\Pagination\LengthAwarePaginator $paginator, string $resourceClass)
    {
        return response()->json([
            'success' => true,
            'data' => $resourceClass::collection($paginator->getCollection())->resolve(),
            'meta' => [
                'current_page' => $paginator->currentPage(),
                'last_page' => $paginator->lastPage(),
                'per_page' => $paginator->perPage(),
                'total' => $paginator->total(),
            ],
        ]);
    }

    protected function error(string $message, int $status = 400, array $errors = [])
    {
        $payload = ['success' => false, 'message' => $message];
        if (!empty($errors)) {
            $payload['errors'] = $errors;
        }

        return response()->json($payload, $status);
    }
}
