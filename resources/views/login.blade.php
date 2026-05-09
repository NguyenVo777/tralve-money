@extends('layouts.app')

@section('title', 'Đăng nhập')
@section('hide_navbar', true)
@section('hide_bottom_nav', true)

@push('styles')
    <style>
        .login-page {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            position: relative;
            background: url('https://images.unsplash.com/photo-1477959858617-67f85cf4f1df?q=80&w=2000&auto=format&fit=crop') center/cover no-repeat;
        }

        .login-page::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(to bottom, rgba(11, 17, 32, 0.8) 0%, rgba(11, 17, 32, 0.95) 100%);
            z-index: 0;
        }

        .login-header {
            position: absolute;
            top: 40px;
            width: 100%;
            text-align: center;
            z-index: 1;
        }

        .login-header .nav-brand {
            justify-content: center;
        }

        .login-box {
            position: relative;
            z-index: 1;
            width: 100%;
            max-width: 440px;
            padding: 40px;
            text-align: center;
            background: rgba(17, 24, 39, 0.6);
            border: 1px solid rgba(255, 255, 255, 0.05);
            box-shadow: 0 0 40px rgba(6, 182, 212, 0.1);
        }

        .login-box h2 {
            font-size: 28px;
            margin-bottom: 8px;
        }

        .login-box>p {
            color: var(--text-muted);
            font-size: 14px;
            margin-bottom: 32px;
        }

        .form-group {
            text-align: left;
            margin-bottom: 20px;
        }

        .input-with-icon {
            position: relative;
        }

        .input-with-icon i {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
            pointer-events: none;
            z-index: 1;
        }

        .input-with-icon input {
            padding-left: 48px;
            background: rgba(0, 0, 0, 0.4);
        }

        .input-with-icon input.is-invalid {
            border-color: #ef4444 !important;
        }

        .invalid-feedback {
            display: block;
            color: #fca5a5;
            font-size: 12px;
            margin-top: 6px;
        }

        .forgot-password {
            float: right;
            font-size: 12px;
            color: var(--primary);
            text-decoration: none;
        }

        .forgot-password:hover {
            text-decoration: underline;
        }

        .divider {
            display: flex;
            align-items: center;
            text-align: center;
            margin: 32px 0;
            color: var(--text-muted);
            font-size: 12px;
            letter-spacing: 1px;
        }

        .divider::before,
        .divider::after {
            content: '';
            flex: 1;
            border-bottom: 1px solid var(--border);
        }

        .divider:not(:empty)::before {
            margin-right: .5em;
        }

        .divider:not(:empty)::after {
            margin-left: .5em;
        }

        .social-login {
            display: flex;
            gap: 16px;
        }

        .social-btn {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 12px;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid var(--border);
            border-radius: 8px;
            color: var(--text-main);
            cursor: pointer;
            transition: 0.3s;
            font-size: 14px;
        }

        .social-btn:hover {
            background: rgba(255, 255, 255, 0.1);
        }

        .footer-status {
            position: absolute;
            bottom: 40px;
            width: 100%;
            display: flex;
            justify-content: center;
            gap: 40px;
            z-index: 1;
            font-size: 12px;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .footer-status i {
            color: var(--primary);
            margin-right: 8px;
        }

        /* Alerts */
        .alert {
            padding: 12px 16px;
            border-radius: 8px;
            font-size: 13px;
            margin-bottom: 24px;
            text-align: left;
            display: flex;
            align-items: flex-start;
            gap: 10px;
        }

        .alert i {
            margin-top: 1px;
            flex-shrink: 0;
        }

        .alert-danger {
            background: rgba(239, 68, 68, 0.12);
            border: 1px solid rgba(239, 68, 68, 0.35);
            color: #fca5a5;
        }

        .alert-success {
            background: rgba(6, 182, 212, 0.12);
            border: 1px solid rgba(6, 182, 212, 0.35);
            color: #67e8f9;
        }
    </style>
@endpush

@section('content')
    <div class="login-page">

        <div class="login-header">
            <a href="{{ route('home') }}" class="nav-brand">
                Smart<span>Travel</span>
            </a>
        </div>

        <div class="glass-card login-box">
            <h2>Chào mừng quay trở lại</h2>
            <p>Đăng nhập vào dịch vụ quản gia toàn cầu của bạn</p>

            @if (session('error'))
                <div class="alert alert-danger">
                    <i class="fa-solid fa-circle-exclamation"></i>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            @if (session('success'))
                <div class="alert alert-success">
                    <i class="fa-solid fa-circle-check"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            <form action="{{ route('login') }}" method="POST" novalidate>
                @csrf

                {{-- Email --}}
                <div class="form-group">
                    <label class="form-label" style="font-size: 12px; text-transform: uppercase; letter-spacing: 1px;">
                        ĐỊA CHỈ EMAIL
                    </label>
                    <div class="input-with-icon">
                        <i class="fa-regular fa-envelope"></i>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                            placeholder="concierge@smarttravel.com" value="{{ old('email') }}" autocomplete="email"
                            autofocus>
                    </div>
                    @error('email')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Password --}}
                <div class="form-group">
                    <label class="form-label" style="font-size: 12px; text-transform: uppercase; letter-spacing: 1px;">
                        MẬT KHẨU
                        <a href="#" class="forgot-password">Quên mật khẩu?</a>
                    </label>
                    <div class="input-with-icon">
                        <i class="fa-solid fa-lock"></i>
                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                            placeholder="••••••••" autocomplete="current-password">
                    </div>
                    @error('password')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 8px;">
                    Đăng nhập
                </button>
            </form>

            <div class="divider">HOẶC TIẾP TỤC VỚI</div>

            <div class="social-login">
                <button class="social-btn"><i class="fa-brands fa-google"></i> Google</button>
                <button class="social-btn"><i class="fa-brands fa-apple"></i> Apple</button>
            </div>

            <p style="margin-top: 32px; margin-bottom: 0;">
                Bạn chưa có tài khoản?
                <a href="{{ route('register') }}" style="font-weight: 600;">Đăng ký</a>
            </p>
        </div>

        <div class="footer-status">
            <span><i class="fa-solid fa-circle" style="font-size: 8px;"></i> TRẠNG THÁI TOÀN CẦU: TRỰC TUYẾN</span>
            <span><i class="fa-solid fa-shield-check"></i> BẮT TAY BẢO MẬT</span>
        </div>

    </div>
@endsection