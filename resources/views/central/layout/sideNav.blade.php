<nav class="sidebar sidebar-offcanvas" id="sidebar">
    <div class="user-profile">
      <div class="user-name">
          username : {{ admin()?->name }}
      </div>
      <div class="user-designation">
          Role : Admin
      </div>
    </div>
    <ul class="nav">
        {{-- <li class="nav-item">
            <a class="nav-link" href="/admin">
              <i class="icon-box menu-icon"></i>
              <span class="menu-title">Dashboard</span>
            </a>
        </li> --}}
        <li class="nav-item">
            <a class="nav-link" href="/admin/brands">
                <i class="icon-box menu-icon"></i>
                <span class="menu-title">Brands</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="/admin/categories">
                <i class="icon-box menu-icon"></i>
                <span class="menu-title">Categories</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="/admin/countries">
                <i class="icon-box menu-icon"></i>
                <span class="menu-title">Countries</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="/admin/currencies">
                <i class="icon-box menu-icon"></i>
                <span class="menu-title">Currencies</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="/admin/shipping-companies">
                <i class="icon-box menu-icon"></i>
                <span class="menu-title">Shipping Companies</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="/admin/users">
                <i class="icon-box menu-icon"></i>
                <span class="menu-title">Users</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="/admin/admins">
                <i class="icon-box menu-icon"></i>
                <span class="menu-title">Admins</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="/admin/partners">
                <i class="icon-box menu-icon"></i>
                <span class="menu-title">Partners (Affiliate)</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="/admin/parameters">
                <i class="icon-box menu-icon"></i>
                <span class="menu-title">Parameters</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="/admin/products">
                <i class="icon-box menu-icon"></i>
                <span class="menu-title">Products</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="/admin/blog-categories">
                <i class="icon-box menu-icon"></i>
                <span class="menu-title">Blog Categories</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="/admin/blogs">
                <i class="icon-box menu-icon"></i>
                <span class="menu-title">Blogs</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="/admin/sliders">
                <i class="icon-box menu-icon"></i>
                <span class="menu-title">Sliders</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="/admin/orders">
                <i class="icon-box menu-icon"></i>
                <span class="menu-title">Orders</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="/admin/contacts">
                <i class="icon-box menu-icon"></i>
                <span class="menu-title">Contact Messages</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="/admin/settings">
                <i class="icon-cog menu-icon"></i>
                <span class="menu-title">Settings</span>
            </a>
        </li>
    </ul>
  </nav>
