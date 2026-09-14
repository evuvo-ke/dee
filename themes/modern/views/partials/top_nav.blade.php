<nav class="main-header navbar modern-topbar">
    <button class="nav-trigger" data-widget="pushmenu" type="button" aria-label="Toggle menu"><i class="fas fa-bars"></i></button>
    <div class="topbar-context"><span>{{ now()->format('l, j F') }}</span><strong>Good {{ now()->hour < 12 ? 'morning' : (now()->hour < 17 ? 'afternoon' : 'evening') }}, {{ Auth::user()->first_name }}</strong></div>
    <div class="topbar-actions"><a href="{{ url('user/profile/notification') }}" class="icon-button" aria-label="Notifications"><i class="far fa-bell"></i>@if(Auth::user()->unreadNotifications()->count())<span>{{ Auth::user()->unreadNotifications()->count() }}</span>@endif</a><a href="{{ url('user/profile') }}" class="profile-chip"><span>{{ mb_substr(Auth::user()->first_name, 0, 1) }}{{ mb_substr(Auth::user()->last_name, 0, 1) }}</span><b>{{ Auth::user()->full_name }}</b></a></div>
</nav>
