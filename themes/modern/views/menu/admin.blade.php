<aside class="main-sidebar modern-sidebar">
    <a href="{{ url('/') }}" class="brand-link">
        <span class="brand-mark">{{ mb_substr($company_name ?? 'L', 0, 1) }}</span>
        <span class="brand-text">{{ $company_name ?? 'Loan Manager' }}<small>Financial workspace</small></span>
    </a>
    <div class="sidebar">
        <div class="user-panel"><span class="user-avatar">{{ mb_substr(Auth::user()->first_name, 0, 1) }}{{ mb_substr(Auth::user()->last_name, 0, 1) }}</span><div><strong>{{ Auth::user()->full_name }}</strong><small>{{ Auth::user()->getRoleNames()->first() ?: 'Team member' }}</small></div></div>
        <nav aria-label="Primary navigation"><ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">
            @foreach(\Modules\Core\Entities\Menu::with('children')->where('is_parent', 1)->orderBy('menu_order')->get() as $parent)
                @continue($parent->permissions && !Auth::user()->can($parent->permissions))
                <li class="nav-item {{ $parent->children->count() ? 'has-treeview' : '' }} {{ Request::is($parent->url.'*') ? 'menu-open' : '' }}">
                    <a href="{{ $parent->children->count() ? '#' : url($parent->url) }}" class="nav-link {{ Request::is($parent->url.'*') ? 'active' : '' }}"><i class="nav-icon fas {{ $parent->icon }}"></i><p>{{ $parent->name }} @if($parent->children->count())<i class="right fas fa-angle-left"></i>@endif</p></a>
                    @if($parent->children->count())<ul class="nav nav-treeview">@foreach($parent->children as $child) @if(!$child->permissions || Auth::user()->can($child->permissions))<li class="nav-item"><a href="{{ url($child->url) }}" class="nav-link {{ Request::is($child->url.'*') ? 'active' : '' }}"><i class="nav-icon fas {{ $child->icon }}"></i><p>{{ $child->name }}</p></a></li>@endif @endforeach</ul>@endif
                </li>
            @endforeach
        </ul></nav>
    </div>
</aside>
