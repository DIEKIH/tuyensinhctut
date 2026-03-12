<nav class="sidebar">
    <div class="sidebar-header">
        <a href="#" class="logo">
            Modernize
        </a>
    </div>

    <div class="sidebar-nav">
        <div class="nav-section">
            <div class="nav-item">
                <a href="{{ url('/admin/dashboard') }}" class="nav-link {{ request()->is('admin/dashboard') ? 'active' : '' }}">
                    <i class="fas fa-tachometer-alt"></i>
                    Dashboard
                </a>
            </div>

            <div class="nav-item">
                <a href="{{ url('admin/baiviet') }}" class="nav-link {{ request()->is('admin/baiviet*') ? 'active' : '' }}">
                    <i class="fas fa-newspaper"></i> Bài viết
                </a>
            </div>

            <div class="nav-item">
                <a href="#" class="nav-link">
                    <i class="fas fa-box"></i>
                    Products
                </a>
            </div>
            <div class="nav-item">
                <a href="#" class="nav-link">
                    <i class="fas fa-tags"></i>
                    Categories
                </a>
            </div>
            <div class="nav-item">
                <a href="#" class="nav-link">
                    <i class="fas fa-users"></i>
                    Customers
                </a>
            </div>
            <div class="nav-item">
                <a href="#" class="nav-link">
                    <i class="fas fa-chart-bar"></i>
                    Reports
                </a>
            </div>
            <div class="nav-item">
                <a href="#" class="nav-link">
                    <i class="fas fa-ticket-alt"></i>
                    Coupons
                </a>
            </div>
            <div class="nav-item">
                <a href="#" class="nav-link">
                    <i class="fas fa-inbox"></i>
                    Inbox
                </a>
            </div>
        </div>

        <div class="nav-section">
            <div class="nav-section-title">Authentication</div>
            @if(session()->has('user'))
                <div class="nav-item">
                    <a href="{{ url('/logout') }}" class="nav-link"
                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="fas fa-sign-out-alt"></i>
                        Logout
                    </a>
                    <form id="logout-form" action="{{ url('/logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </div>
            @else
                <div class="nav-item">
                    <a href="{{ url('/login') }}" class="nav-link">
                        <i class="fas fa-sign-in-alt"></i>
                        Sign In
                    </a>
                </div>
            @endif
        </div>
    </div>
</nav>
