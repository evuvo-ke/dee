<nav class="mobile-dock" aria-label="Mobile navigation">
    @if($role === 'client')
        <a href="{{ url('portal/dashboard') }}" class="{{ Request::is('portal/dashboard') ? 'active' : '' }}"><i class="fas fa-home"></i><span>Home</span></a>
        <a href="{{ url('portal/loan') }}" class="{{ Request::is('portal/loan*') ? 'active' : '' }}"><i class="fas fa-wallet"></i><span>Loans</span></a>
        <a href="{{ url('portal/loan') }}" class="dock-action"><i class="fas fa-arrow-up"></i><span>Pay</span></a>
        <a href="{{ url('portal/loan/application') }}"><i class="fas fa-receipt"></i><span>History</span></a>
        <a href="{{ url('portal/client') }}"><i class="fas fa-user"></i><span>Profile</span></a>
    @else
        <a href="{{ url('dashboard') }}" class="{{ Request::is('dashboard*') ? 'active' : '' }}"><i class="fas fa-chart-pie"></i><span>Home</span></a>
        @can('loan.index')<a href="{{ url('loan') }}" class="{{ Request::is('loan*') ? 'active' : '' }}"><i class="fas fa-wallet"></i><span>Loans</span></a>@endcan
        @can('client.index')<a href="{{ url('client') }}" class="{{ Request::is('client*') ? 'active' : '' }}"><i class="fas fa-users"></i><span>Clients</span></a>@endcan
        <a href="{{ url('user/profile') }}"><i class="fas fa-user"></i><span>Profile</span></a>
    @endif
</nav>
