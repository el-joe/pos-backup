<?php
    $canAccess = isset($data['can']) ? adminCan($data['can']) : true;
    $title = __($data['translated_title'] ?? $data['title'] ?? '');
?>

@if(isset($data['children']) && count($data['children']) > 0)
    @php
        $isActive = false;
        $flattenToLastChild = extractRoutes($data['children']);

        foreach ($flattenToLastChild as $child) {
            $checkRouteParams = true;

            if(isset($child['route_params'])){
                $checkRouteParams = checkRouteParams($child['route_params']);
            } elseif(isset($child['request_params'])){
                $checkRouteParams = checkRequestParams($child['request_params']);
            }

            $isActive = request()->routeIs($child['route']) && $checkRouteParams;

            if($isActive) {
                break;
            }
        }

        $checkSubscriptionStatus = subscriptionFeatureEnabled($data['subscription_check'] ?? null, true, $__current_subscription ?? null);
    @endphp

    @if((!isset($data['subscription_check']) || (isset($data['subscription_check']) && $checkSubscriptionStatus)) && $canAccess)
        <li class="{{ $isActive ? 'active' : '' }}">
            <a class="waves-effect" href="javascript:void(0);" aria-expanded="{{ $isActive ? 'true' : 'false' }}">
                <i class="{{ $data['icon'] }}"></i>
                <span class="hide-menu"> {{ $title }} </span>
            </a>
            <ul aria-expanded="{{ $isActive ? 'true' : 'false' }}" class="collapse{{ $isActive ? ' in' : '' }}">
                @foreach ($data['children'] as $child)
                    @include('layouts.admin.partials.sidebar-ul', ['data' => $child])
                @endforeach
            </ul>
        </li>
    @endif
@else
    @php
        $checkRouteParams = true;

        if(isset($data['route_params'])){
            $checkRouteParams = checkRouteParams($data['route_params']);
        }

        if(isset($data['request_params'])){
            $checkRouteParams = checkRequestParams($data['request_params']);
        }

        if(request()->routeIs($data['route'])){
            $checkRouteParams = $checkRouteParams && true;
        }else{
            $checkRouteParams = false;
        }

        $enabled = true;
        if(isset($data['enabled'])){
            $enabled = !!tenantSetting($data['enabled']);
        }

        $subscriptionCheckStatus = subscriptionFeatureEnabled($data['subscription_check'] ?? null, true, $__current_subscription ?? null);
        $href = $data['route'] == '#' ? '#' : route($data['route'], $data['route_params'] ?? $data['request_params'] ?? null);
        $isActive = request()->routeIs($data['route']) && $checkRouteParams;
    @endphp

    @if((!isset($data['subscription_check']) || (isset($data['subscription_check']) && $subscriptionCheckStatus)) && $canAccess && $enabled)
        <li class="{{ $isActive ? 'active' : '' }}">
            <a href="{{ $href }}" aria-expanded="{{ $isActive ? 'true' : 'false' }}">
                <i class="{{ $data['icon'] }}"></i>
                <span class="hide-menu"> {{ $title }} </span>
            </a>
        </li>
    @endif
@endif
