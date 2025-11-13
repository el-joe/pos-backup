@if(isset($data['children']) && count($data['children']) > 0)
    <?php
        $isActive = false;
        $flattenToLastChild = extractRoutes($data['children']);

        foreach ($flattenToLastChild as $child){
            if(request()->routeIs($child)){
                $isActive = true;
                break;
            }
        }
    ?>
<div class="menu-item has-sub {{ $isActive ? 'active' : '' }} mb-1">
    <a href="#" class="menu-link">
        <span class="menu-icon">
            <i class="{{$data['icon']}}"></i>
        </span>
        <span class="menu-text">{{$data['title']}}</span>
        <span class="menu-caret"><b class="caret"></b></span>
    </a>
    <div class="menu-submenu">
        @foreach ($data['children'] as $child)
            {!! sidebarHud($child) !!}
        @endforeach
    </div>
</div>
@else
    <div class="menu-item {{ request()->routeIs($data['route']) ? 'active' : '' }}  mb-1">
        <a href="{{ $data['route'] == "#" ? "#" : route($data['route']) }}" class="menu-link">
            <span class="menu-icon"><i class="{{$data['icon']}}"></i></span>
            <span class="menu-text">{{$data['title']}}</span>
        </a>
    </div>
@endif
