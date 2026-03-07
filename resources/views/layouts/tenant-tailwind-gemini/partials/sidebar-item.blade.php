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

        $checkSubscriptionStatus = subscriptionFeatureEnabled($data['subscription_check'] ?? null, true, $__current_subscription ?? null);
    ?>
    @if((!isset($data['subscription_check']) || (isset($data['subscription_check']) && $checkSubscriptionStatus)) && $canAccess)
        <div x-data="{ open: {{ $isActive ? 'true' : 'false' }} }" class="rounded-2xl border border-slate-200/80 bg-white/70 p-1 dark:border-slate-800 dark:bg-slate-900/60">
            <button type="button" @click="open = !open" class="flex w-full items-center justify-between rounded-xl px-3 py-2 text-start transition {{ $isActive ? 'bg-brand-50 text-brand-700 dark:bg-brand-500/10 dark:text-brand-300' : 'text-slate-600 hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-slate-800' }}">
                <span class="flex items-center gap-3">
                    <i class="{{ $data['icon'] }} w-5 text-center"></i>
                    <span class="text-sm font-medium">{{ __($data['translated_title']) }}</span>
                </span>
                <i class="fa fa-chevron-down text-xs transition-transform" :class="open ? 'rotate-180' : ''"></i>
            </button>

            <div x-show="open" x-collapse class="space-y-1 px-2 pb-2 pt-1" x-cloak>
                @foreach ($data['children'] as $child)
                    {!! sidebarGemini($child) !!}
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
        $link = $data['route'] == '#' ? '#' : panelAwareUrl(route($data['route'], $data['route_params'] ?? $data['request_params'] ?? null));
    @endphp
    @if((!isset($data['subscription_check']) || (isset($data['subscription_check']) && $subscriptionCheckStatus)) && $canAccess && $enabled)
        <a href="{{ $link }}" class="flex items-center gap-3 rounded-xl px-3 py-2 text-sm font-medium transition {{ request()->routeIs($data['route']) && $checkRouteParams ? 'bg-brand-600 text-white shadow-lg shadow-brand-600/20' : 'text-slate-600 hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-slate-800' }}">
            <i class="{{ $data['icon'] }} w-5 text-center"></i>
            <span>{{ __($data['translated_title']) }}</span>
        </a>
    @endif
@endif
