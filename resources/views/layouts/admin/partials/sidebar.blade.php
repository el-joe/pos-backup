<aside class="sidebar">
    <div class="scroll-sidebar">
        <div class="user-profile">
            <div class="dropdown user-pro-body">
                <div class="profile-image">
                    <img src="{{ admin()->image_path }}" alt="user-img" class="img-circle">
                    <a href="javascript:void(0);" class="dropdown-toggle u-dropdown text-blue" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                        <span class="badge badge-danger">
                            <i class="fa fa-angle-down"></i>
                        </span>
                    </a>
                    <ul class="dropdown-menu animated flipInY">
                        {{-- <li><a href="javascript:void(0);"><i class="fa fa-user"></i> Profile</a></li>
                        <li><a href="javascript:void(0);"><i class="fa fa-inbox"></i> Inbox</a></li> --}}
                        {{-- <li role="separator" class="divider"></li> --}}
                        {{-- <li><a href="javascript:void(0);"><i class="fa fa-cog"></i> Account Settings</a></li> --}}
                        {{-- <li role="separator" class="divider"></li> --}}
                        <li><a href="/logout"><i class="fa fa-power-off"></i> Logout</a></li>
                    </ul>
                </div>
                <p class="profile-text m-t-15 font-16"><a href="javascript:void(0);"> {{ admin()->name }}</a></p>
            </div>
        </div>
        <nav class="sidebar-nav">
            <ul id="side-menu">
                @foreach (config('sidebar-links') as $sidebarData)
                    @include('layouts.admin.partials.sidebar-ul', ['data' => $sidebarData])
                @endforeach
            </ul>
        </nav>
    </div>
</aside>
