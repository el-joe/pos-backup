<?php
    $canAccess = isset($data['can']) ? adminCan($data['can']) : true;
    $depth = $depth ?? 0;
    $isTopLevel = $depth === 0;
    $labelVisibility = $isTopLevel ? 'sidebarOpen || !isDesktop' : 'true';
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
        <div x-data="{ open: {{ $isActive ? 'true' : 'false' }} }" class="relative" @click.away="if (isDesktop && !sidebarOpen && {{ $isTopLevel ? 'true' : 'false' }}) open = false">
            <button type="button" @click="open = !open" class="w-full rounded-lg py-2 text-start transition {{ $isActive ? 'bg-blue-50 text-blue-600 dark:bg-blue-900/20 dark:text-blue-400' : 'text-gray-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700' }}" :class="(!sidebarOpen && isDesktop && {{ $isTopLevel ? 'true' : 'false' }}) ? 'flex items-center justify-center px-2' : 'flex items-center justify-between px-3'">
                <span class="flex items-center gap-3">
                    <i class="{{ $data['icon'] }} w-6 flex-shrink-0 text-center"></i>
                    <span x-show="{{ $labelVisibility }}" x-cloak class="text-sm font-medium transition-opacity">{{ __($data['translated_title']) }}</span>
                </span>
                <i x-show="{{ $labelVisibility }}" x-cloak class="fa fa-chevron-down text-xs transition-transform" :class="open ? 'rotate-180' : ''"></i>
            </button>

            <div x-show="open" x-cloak :class="(!sidebarOpen && isDesktop && {{ $isTopLevel ? 'true' : 'false' }}) ? 'absolute top-0 z-[60] w-56 rounded-xl border border-gray-200 bg-white py-2 shadow-xl dark:border-gray-700 dark:bg-gray-800 ltr:left-full ltr:ml-3 rtl:right-full rtl:mr-3' : '{{ $isTopLevel ? 'mt-1 space-y-1 ltr:ml-9 rtl:mr-9' : 'mt-1 space-y-1 ltr:ml-5 rtl:mr-5' }}'" @if($isTopLevel) x-transition:enter="transition ease-out duration-100" x-transition:enter-start="opacity-0 scale-95" @else x-collapse @endif>
                @if($isTopLevel)
                    <div x-show="!sidebarOpen && isDesktop" x-cloak class="border-b border-gray-100 px-4 py-2 text-xs font-bold uppercase tracking-[0.2em] text-gray-400 dark:border-gray-700">{{ __($data['translated_title']) }}</div>
                @endif
                @foreach ($data['children'] as $child)
                    {!! view('layouts.tenant-tailwind-gemini.partials.sidebar-item', ['data' => $child, 'depth' => $depth + 1])->render() !!}
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
        <a href="{{ $link }}" class="group relative rounded-lg py-2 text-sm font-medium transition {{ request()->routeIs($data['route']) && $checkRouteParams ? 'bg-blue-50 text-blue-600 dark:bg-blue-900/20 dark:text-blue-400' : 'text-gray-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700' }}" :class="(!sidebarOpen && isDesktop && {{ $isTopLevel ? 'true' : 'false' }}) ? 'flex items-center justify-center px-2' : 'flex items-center gap-3 px-3'" @if($isTopLevel) :title="(!sidebarOpen && isDesktop) ? '{{ __($data['translated_title']) }}' : ''" @endif>
            <i class="{{ $data['icon'] }} w-6 flex-shrink-0 text-center"></i>
            <span x-show="{{ $labelVisibility }}" x-cloak class="transition-opacity">{{ __($data['translated_title']) }}</span>
        </a>
    @endif
@endif
