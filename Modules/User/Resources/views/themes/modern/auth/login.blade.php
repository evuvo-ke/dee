@extends('core::layouts.auth')
@section('title', trans_choice('user::general.login', 1))
@section('content')
<main class="auth-shell">
    <section class="auth-story" aria-label="Welcome">
        <div class="brand-mark">{{ mb_substr($company_name ?? 'L', 0, 1) }}</div>
        <div><p class="auth-kicker">Clear credit. Human service.</p><h1>Your financial work, finally in focus.</h1><p>Review loans, manage repayments and keep every customer relationship moving from one secure workspace.</p></div>
        <small>Protected access · {{ now()->year }}</small>
    </section>
    <section class="auth-panel">
        <p class="auth-kicker">Secure sign in</p><h2>Welcome back</h2><p class="text-muted mb-4">Enter your details to continue to {{ $company_name ?? 'your account' }}.</p>
        <form method="post" action="{{ route('login') }}">@csrf
            <div class="form-group"><label for="email">{{ trans_choice('user::general.email', 1) }}</label><input id="email" type="email" name="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror" autocomplete="email" required autofocus>@error('email')<span class="invalid-feedback">{{ $message }}</span>@enderror</div>
            <div class="form-group"><div class="d-flex justify-content-between"><label for="password">{{ trans_choice('user::general.password', 1) }}</label><a href="{{ route('password.request') }}">{{ trans_choice('user::general.forgot_password', 1) }}</a></div><div class="password-wrap"><input id="password" type="password" name="password" class="form-control pr-5 @error('password') is-invalid @enderror" autocomplete="current-password" required><button class="password-toggle" type="button" data-password-toggle="password" aria-label="Show password"><i class="far fa-eye"></i></button>@error('password')<span class="invalid-feedback">{{ $message }}</span>@enderror</div></div>
            <div class="custom-control custom-checkbox mb-4"><input class="custom-control-input" id="remember" name="remember" type="checkbox" {{ old('remember') ? 'checked' : '' }}><label class="custom-control-label" for="remember">{{ trans_choice('user::general.remember_me', 1) }}</label></div>
            <button type="submit" class="btn btn-primary btn-block">{{ trans_choice('user::general.login', 1) }}</button>
        </form>
    </section>
</main>
@endsection
