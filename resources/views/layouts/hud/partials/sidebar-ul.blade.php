<?php
    $canAccess = isset($data['can']) ? adminCan($data['can']) : true;
?>
@if(isset($data['children']) && count($data['children']) > 0)
    <?php
        $isActive = false;
        $flattenToLastChild = extractRoutes($data['children']);

        foreach($data['children'] as $child){
            if(isset($child['route_params'])){
                $checkRouteParams = checkRouteParams($child['route_params']);
            }
            if(isset($child['request_params'])){
                $checkRouteParams = checkRequestParams($child['request_params']);
            }

            if(isset($checkRouteParams) && $checkRouteParams){
                break;
            }
        }

        foreach ($flattenToLastChild as $child){
            $isActive = request()->routeIs($child['route']);

            if(isset($child['route_params'])){
                $checkRouteParams = checkRouteParams($child['route_params']);
            }elseif(isset($child['request_params'])){
                $checkRouteParams = checkRequestParams($child['request_params']);
            }else{
                $checkRouteParams = true;
            }

            $isActive = $checkRouteParams && $isActive;

            if($isActive)break;
        }

        // if(isset($checkRouteParams)){
        //     $isActive = $isActive && $checkRouteParams;
        // }

        $checkSubscriptionStatus = subscriptionFeatureEnabled($data['subscription_check'] ?? null, true, $__current_subscription ?? null);
    ?>
    @if((!isset($data['subscription_check']) || (isset($data['subscription_check']) && $checkSubscriptionStatus)) && $canAccess)
        <div class="menu-item has-sub {{ $isActive ? 'active' : '' }} mb-1">
            <a href="#" class="menu-link">
                <span class="menu-icon">
                    <i class="{{$data['icon']}}"></i>
                </span>
                <span class="menu-text">{{__($data['translated_title'])}}</span>
                <span class="menu-caret"><b class="caret"></b></span>
            </a>
            <div class="menu-submenu">
                @foreach ($data['children'] as $child)
                    {!! sidebarHud($child) !!}
                @endforeach
            </div>
        </div>
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
    @endphp
    @if((!isset($data['subscription_check']) || (isset($data['subscription_check']) && $subscriptionCheckStatus)) && $canAccess && $enabled)
        <div class="menu-item {{ request()->routeIs($data['route']) && $checkRouteParams ? 'active' : '' }}  mb-1">
            <a href="{{ $data['route'] == "#" ? "#" : route($data['route'],$data['route_params']??$data['request_params']??null) }}" class="menu-link">
                <span class="menu-icon"><i class="{{$data['icon']}}"></i></span>
                <span class="menu-text">{{__($data['translated_title'])}}</span>
            </a>
        </div>
    @endif
@endif
