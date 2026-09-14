@extends('core::layouts.master')
@section('title', trans_choice('dashboard::general.dashboard', 1))
@section('content')
<section class="content pt-3">
    <div class="finance-hero">
        <span class="eyebrow">{{ $isLoanOfficer ? 'Today’s field desk' : 'Portfolio command' }}</span>
        <h1>{{ $isLoanOfficer ? 'Know what needs attention today.' : 'The lending book, without the noise.' }}</h1>
        <p>{{ $isLoanOfficer ? 'Assigned loans, collections and pending work are scoped to your portfolio.' : 'Live figures below are calculated from the current loan ledger.' }}</p>
    </div>
    <div class="metric-grid" aria-label="Portfolio summary">
        <article class="metric-card"><span class="metric-accent"><i class="fas fa-chart-line"></i></span><div class="metric-value">{{ number_format($dashboardMetrics['outstanding'], 2) }}</div><div class="metric-label">Outstanding portfolio</div><p class="metric-note">Balance currently due across {{ $isLoanOfficer ? 'your assigned' : 'all' }} loans</p></article>
        <article class="metric-card"><div class="metric-label">Total portfolio</div><div class="metric-value">{{ number_format($dashboardMetrics['portfolio'], 2) }}</div><span class="metric-note">Principal disbursed</span></article>
        <article class="metric-card"><div class="metric-label">Collected to date</div><div class="metric-value">{{ number_format($dashboardMetrics['collected'], 2) }}</div><span class="metric-note">Approved ledger repayments</span></article>
        <article class="metric-card"><div class="metric-label">Active loans</div><div class="metric-value">{{ number_format($dashboardMetrics['active']) }}</div><span class="metric-note">Currently in repayment</span></article>
        <article class="metric-card"><div class="metric-label">Today’s collections</div><div class="metric-value">{{ number_format($dashboardMetrics['today_collected'], 2) }}</div><span class="metric-note">Recorded today</span></article>
    </div>
    <div class="row mt-4"><div class="col-lg-8"><div class="card"><div class="card-header d-flex align-items-center"><h3 class="card-title mb-0">Recent loans</h3>@can('loan.index')<a href="{{ url('loan') }}" class="ml-auto">View all</a>@endcan</div><div class="card-body p-0"><div class="table-responsive"><table class="table mb-0"><thead><tr><th>Account</th><th>Client</th><th>Status</th><th class="text-right">Outstanding</th></tr></thead><tbody>@forelse($recentLoans as $loan)<tr><td><a href="{{ url('loan/'.$loan->id.'/show') }}">{{ $loan->account_number ?: '#'.$loan->id }}</a></td><td>{{ optional($loan->client)->first_name }} {{ optional($loan->client)->last_name }}</td><td><span class="badge badge-{{ $loan->status === 'active' ? 'success' : ($loan->status === 'pending' ? 'warning' : 'secondary') }}">{{ ucfirst($loan->status) }}</span></td><td class="text-right">{{ optional($loan->currency)->code }} {{ number_format($loan->total_outstanding_derived, 2) }}</td></tr>@empty<tr><td colspan="4"><div class="empty-modern"><i class="fas fa-folder-open"></i><strong>No loans to show</strong><p class="mb-0">Loans appear here as they are created.</p></div></td></tr>@endforelse</tbody></table></div></div></div></div><div class="col-lg-4"><div class="card"><div class="card-header"><h3 class="card-title">Work queue</h3></div><div class="card-body"><div class="d-flex justify-content-between align-items-center mb-3"><span>Awaiting action</span><strong>{{ number_format($dashboardMetrics['pending']) }}</strong></div>@can('client.index')<a class="btn btn-outline-primary btn-block" href="{{ url('client') }}">Find a client</a>@endcan @can('loan.create')<a class="btn btn-primary btn-block" href="{{ url('loan/create') }}">Create loan</a>@endcan</div></div></div></div>
</section>
@endsection
