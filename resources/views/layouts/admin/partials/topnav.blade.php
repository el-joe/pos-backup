<nav class="navbar navbar-default navbar-static-top m-b-0">
    <div class="navbar-header">
        <a class="navbar-toggle font-20 hidden-sm hidden-md hidden-lg " href="javascript:void(0)" data-toggle="collapse" data-target=".navbar-collapse">
            <i class="fa fa-bars"></i>
        </a>
        <div class="top-left-part">
            <a class="logo" href="index.html">
                <b>
                    <img src="{{ asset('adminBoard') }}/plugins/images/logo.png" alt="home" />
                </b>
                <span>
                    <img src="{{ asset('adminBoard') }}/plugins/images/logo-text.png" alt="homepage" class="dark-logo" />
                </span>
            </a>
        </div>
        <ul class="nav navbar-top-links navbar-left hidden-xs">
            <li>
                <a href="javascript:void(0)" class="sidebartoggler font-20 waves-effect waves-light"><i class="icon-arrow-left-circle"></i></a>
            </li>
            <li>
                <form role="search" class="app-search hidden-xs">
                    <i class="icon-magnifier"></i>
                    <input type="text" placeholder="Search..." class="form-control">
                </form>
            </li>
        </ul>

        {{-- Branch selector on right side of top nav --}}
        @php
            $currentBranch = admin()->branch_id;
        @endphp
        <ul class="nav navbar-top-links navbar-right hidden-xs">
            <li class="dropdown">
                <div style="padding:8px 12px;">
                    {{-- <label class="control-label" style="margin-right:8px; color:#666; font-weight:600;">Branch:</label> --}}
                    <select id="branch-switcher" class="form-control" style="display:inline-block; width:auto; min-width:180px;">
                        {{-- <option value="">All Branches</option> --}}
                        @foreach($__branches as $b)
                            <option value="{{ $b->id }}" @if($currentBranch == $b->id) selected @endif>{{ $b->name }}</option>
                        @endforeach
                    </select>
                </div>
            </li>
        </ul>

        <script>
            document.addEventListener('DOMContentLoaded', function(){
                var sel = document.getElementById('branch-switcher');
                if (!sel) return;
                sel.addEventListener('change', function(){
                    window.location.href = '/admin/switch-branch/' + this.value;
                });
            });
        </script>
    </div>
</nav>
