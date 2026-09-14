<aside class="main-sidebar modern-sidebar">
    <a href="{{ url('portal/dashboard') }}" class="brand-link"><span class="brand-mark">{{ mb_substr($company_name ?? 'L', 0, 1) }}</span><span class="brand-text">{{ $company_name ?? 'My account' }}<small>Borrower portal</small></span></a>
    <div class="sidebar">
        <div class="user-panel"><span class="user-avatar">{{ mb_substr(Auth::user()->first_name, 0, 1) }}{{ mb_substr(Auth::user()->last_name, 0, 1) }}</span><div><strong>{{ Auth::user()->full_name }}</strong><small>Client</small></div></div>
        <nav aria-label="Primary navigation"><ul class="nav nav-pills nav-sidebar flex-column">
            <li class="nav-item"><a href="{{ url('portal/dashboard') }}" class="nav-link {{ Request::is('portal/dashboard') ? 'active' : '' }}"><i class="nav-icon fas fa-home"></i><p>Overview</p></a></li>
            <li class="nav-item"><a href="{{ url('portal/loan') }}" class="nav-link {{ Request::is('portal/loan*') ? 'active' : '' }}"><i class="nav-icon fas fa-wallet"></i><p>My loans</p></a></li>
            <li class="nav-item"><a href="{{ url('portal/loan/application') }}" class="nav-link {{ Request::is('portal/loan/application*') ? 'active' : '' }}"><i class="nav-icon fas fa-file-signature"></i><p>Applications</p></a></li>
            <li class="nav-item"><a href="{{ url('portal/client') }}" class="nav-link {{ Request::is('portal/client*') ? 'active' : '' }}"><i class="nav-icon fas fa-user"></i><p>Profile</p></a></li>
        </ul></nav>
    </div>
</aside>
