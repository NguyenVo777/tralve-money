<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SmartTravel Admin — @yield('title', 'Dashboard')</title>

    <link rel="stylesheet" href="{{ asset('css/admin-white.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link
        href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=DM+Sans:opsz,wght@9..40,300;9..40,400;9..40,500;9..40,600;9..40,700&display=swap"
        rel="stylesheet">

    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @stack('styles')
</head>

<body>
    <div class="admin-wrapper">

        <!-- ── SIDEBAR ── -->
        <aside class="premium-sidebar">
            <div class="sidebar-brand">
                <i class="fas fa-globe"></i>
                <span>Smart<span>Travel</span></span>
            </div>

            <nav class="sidebar-nav">
                <div
                    style="font-size:10px;font-weight:700;color:var(--txt3);letter-spacing:.08em;text-transform:uppercase;padding:4px 12px 8px;margin-top:4px">
                    Tổng quan</div>

                <a href="{{ route('admin.dashboard') }}"
                    class="nav-item {{ Request::routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-chart-pie"></i>
                    <span>Dashboard</span>
                </a>

                <div
                    style="font-size:10px;font-weight:700;color:var(--txt3);letter-spacing:.08em;text-transform:uppercase;padding:12px 12px 8px;margin-top:4px">
                    Quản lý</div>

                <a href="{{ route('admin.users') }}"
                    class="nav-item {{ Request::routeIs('admin.users*') ? 'active' : '' }}">
                    <i class="fas fa-users"></i>
                    <span>Người Dùng</span>
                </a>

                <a href="{{ route('admin.rates') }}"
                    class="nav-item {{ Request::routeIs('admin.rates*') ? 'active' : '' }}">
                    <i class="fas fa-exchange-alt"></i>
                    <span>Quản Lý Tỷ Giá</span>
                </a>
            </nav>

            <!-- Bottom: user + logout -->
            <div style="padding:16px 12px;border-top:1px solid var(--border);">
                <div
                    style="display:flex;align-items:center;gap:10px;padding:10px 12px;border-radius:var(--radius-md);background:var(--bg-subtle);margin-bottom:8px;">
                    <img src="{{ Auth::user()->avatar ? asset('storage/' . Auth::user()->avatar) : asset('images/default-avatar.png') }}"
                        style="width:32px;height:32px;border-radius:50%;object-fit:cover;border:1.5px solid var(--border);">
                    <div>
                        <div style="font-size:12.5px;font-weight:600;color:var(--txt);">{{ Auth::user()->full_name }}
                        </div>
                        <div style="font-size:11px;color:var(--txt3);">Quản trị viên</div>
                    </div>
                </div>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="nav-item" style="color:var(--danger);width:100%;">
                        <i class="fas fa-sign-out-alt"></i>
                        <span>Đăng Xuất</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- ── MAIN ── -->
        <main class="premium-main">

            <!-- Top Header -->
            <header class="page-header">
                <div class="page-title">
                    <h1>@yield('header_title', 'Dashboard')</h1>
                    <p class="text-muted">Hệ thống quản trị Smart Travel CMS</p>
                </div>

                <div style="display:flex;align-items:center;gap:12px;">
                    <div class="header-search">
                        <i class="fas fa-search"></i>
                        <input type="text" placeholder="Tìm kiếm...">
                    </div>

                    <button class="notif-btn">
                        <i class="fas fa-bell"></i>
                        <span class="notif-badge">3</span>
                    </button>

                    <div class="user-chip">
                        <div style="text-align:right;">
                            <div class="name">{{ Auth::user()->full_name }}</div>
                            <div class="role">Quản trị viên</div>
                        </div>
                        <img src="{{ Auth::user()->avatar ? asset('storage/' . Auth::user()->avatar) : asset('images/default-avatar.png') }}"
                            alt="{{ Auth::user()->full_name }}">
                    </div>
                </div>
            </header>

            @if(session('success'))
                <div class="alert-success">
                    <i class="fas fa-check-circle"></i>
                    {{ session('success') }}
                </div>
            @endif

            <div class="content-wrapper">
                @yield('content')
            </div>

        </main>
    </div>

    @stack('scripts')
</body>

</html>