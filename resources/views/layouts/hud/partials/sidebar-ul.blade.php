@if(isset($data['children']) && count($data['children']) > 0)
    <?php
        $isActive = false;
        $flattenToLastChild = extractRoutes($data['children']);

        foreach($data['children'] as $child){
            if(isset($child['route_params'])){
                $checkRouteParams = checkRouteParams($child['route_params']);
            }
        }

        foreach ($flattenToLastChild as $child){
            if(request()->routeIs($child)){
                $isActive = true;
                break;
            }
        }

        if(isset($checkRouteParams)){
            $isActive = $isActive && $checkRouteParams;
        }
    ?>
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
@else
    @php
        $checkRouteParams = ($data['route_params'] ??false) ? checkRouteParams($data['route_params']) : true;
    @endphp
    <div class="menu-item {{ request()->routeIs($data['route']) && $checkRouteParams ? 'active' : '' }}  mb-1">
        <a href="{{ $data['route'] == "#" ? "#" : route($data['route'],$data['route_params']??null) }}" class="menu-link">
            <span class="menu-icon"><i class="{{$data['icon']}}"></i></span>
            <span class="menu-text">{{__($data['translated_title'])}}</span>
        </a>
    </div>
@endif
