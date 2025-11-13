@if(isset($data['children']) && count($data['children']) > 0)
<div class="menu-item has-sub">
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
    <div class="menu-item">
        <a href="{{ $data['route'] == "#" ? "#" : route($data['route']) }}" class="menu-link">
            <span class="menu-icon"><i class="{{$data['icon']}}"></i></span>
            <span class="menu-text">{{$data['title']}}</span>
        </a>
    </div>
@endif
