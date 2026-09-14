<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <title>@yield('title') · {{ $company_name ?? 'Loan Manager' }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Manrope:wght@600;700;800&display=swap">
    <link rel="stylesheet" href="{{ asset('themes/adminlte/css/adminlte.css') }}">
    <link rel="stylesheet" href="{{ asset('themes/modern/css/modern.css') }}">
    @yield('styles')
    <script src="{{ asset('themes/adminlte/js/adminlte.js') }}"></script>
</head>
@php
    $role = Auth::user()->hasRole('client') ? 'client' : (Auth::user()->hasAnyRole(['loan officer', 'loan-officer', 'field officer']) ? 'loan-officer' : 'admin');
@endphp
<body class="hold-transition sidebar-mini layout-fixed role-{{ $role }}">
<div class="wrapper ledger-shell">
    @include('core::partials.top_nav')
    @include(Auth::user()->hasRole('client') ? 'core::menu.client' : 'core::menu.admin')
    <main class="content-wrapper" id="main-content">
        <div class="modern-flash">@include('core::partials.flash.message')</div>
        @yield('content')
    </main>
    @include('core::partials.bottom_nav', ['role' => $role])
    @include('core::partials.footer')
</div>
<script src="{{ asset('themes/adminlte/js/custom.js') }}"></script>
<script src="{{ asset('themes/modern/js/modern.js') }}"></script>
@yield('scripts')
</body>
</html>
