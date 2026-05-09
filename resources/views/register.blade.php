@extends('layouts.app')

@section('title', 'Đăng ký')
@section('hide_navbar', true)
@section('hide_bottom_nav', true)

@push('styles')
    <style>
        .auth-container {
            display: flex;
            min-height: 100vh;
            width: 100%;
        }

        .auth-left {
            flex: 1;
            padding: 10%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            position: relative;
            background: radial-gradient(circle at 0% 50%, rgba(6, 182, 212, 0.15) 0%, transparent 50%);
        }

        .auth-right {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px;
        }

        .hero-title {
            font-size: 64px;
            font-weight: 800;
            line-height: 1.1;
            margin-bottom: 24px;
        }

        .hero-title span {
            color: var(--primary);
        }

        .hero-desc {
            font-size: 18px;
            color: var(--text-muted);
            max-width: 400px;
            line-height: 1.6;
            margin-bottom: 40px;
        }

        .feature-list {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .feature-item {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .feature-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.05);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-main);
            border: 1px solid var(--border);
            flex-shrink: 0;
        }

        .auth-box {
            width: 100%;
            max-width: 480px;
        }

        .auth-box h2 {
            font-size: 32px;
            margin-bottom: 8px;
        }

        .auth-box>p {
            color: var(--text-muted);
            margin-bottom: 32px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-row {
            display: flex;
            gap: 16px;
        }

        .form-row .form-group {
            flex: 1;
            min-width: 0;
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

        .checkbox-group {
            display: flex;
            gap: 12px;
            align-items: flex-start;
            margin-bottom: 24px;
            font-size: 14px;
            color: var(--text-muted);
        }

        .checkbox-group input {
            margin-top: 4px;
            accent-color: var(--primary);
            flex-shrink: 0;
        }

        .checkbox-group a {
            color: var(--primary);
            text-decoration: none;
        }

        .checkbox-group a:hover {
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
            margin-right: .25em;
        }

        .divider:not(:empty)::after {
            margin-left: .25em;
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
            background: transparent;
            border: 1px solid var(--border);
            border-radius: 8px;
            color: var(--text-main);
            cursor: pointer;
            transition: 0.3s;
        }

        .social-btn:hover {
            background: rgba(255, 255, 255, 0.05);
        }

        /* Alerts */
        .alert {
            padding: 12px 16px;
            border-radius: 8px;
            font-size: 13px;
            margin-bottom: 24px;
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

        /* Password strength */
        .strength-bar {
            height: 3px;
            border-radius: 2px;
            background: rgba(255, 255, 255, 0.08);
            margin-top: 8px;
            overflow: hidden;
        }

        .strength-fill {
            height: 100%;
            width: 0%;
            border-radius: 2px;
            transition: width 0.3s ease, background-color 0.3s ease;
        }

        .strength-text {
            font-size: 11px;
            margin-top: 4px;
            color: var(--text-muted);
            transition: color 0.3s;
        }

        @media (max-width: 900px) {
            .auth-container {
                flex-direction: column;
            }

            .auth-left {
                padding: 40px 5%;
                text-align: center;
                align-items: center;
            }

            .hero-title {
                font-size: 40px;
            }
        }

        @media (max-width: 520px) {
            .form-row {
                flex-direction: column;
            }

            .auth-right {
                padding: 24px 16px;
            }
        }
    </style>
@endpush

@section('content')
    <div class="animated-bg"></div>

    <div class="auth-container">
        {{-- Left panel --}}
        <div class="auth-left">
            <h1 class="hero-title">Nâng tầm<br><span>hành trình</span></h1>
            <p class="hero-desc">Truy cập hệ sinh thái du lịch kỹ thuật số tinh vi nhất thế giới. Quét thời gian thực, định
                tuyến thông minh và dịch vụ quản gia cao cấp ngay trong tầm tay bạn.</p>

            <div class="feature-list">
                <div class="feature-item">
                    <div class="feature-icon"><i class="fa-solid fa-shield-check"></i></div>
                    <span>Tích hợp sinh trắc học bảo mật</span>
                </div>
                <div class="feature-item">
                    <div class="feature-icon"><i class="fa-solid fa-compass"></i></div>
                    <span>Mạng lưới quản gia toàn cầu</span>
                </div>
            </div>
        </div>

        {{-- Right panel --}}
        <div class="auth-right">
            <div class="glass-card auth-box">
                <h2>Tạo tài khoản</h2>
                <p>Nhập thông tin chi tiết của bạn để bắt đầu hành trình đẳng cấp.</p>

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <i class="fa-solid fa-circle-exclamation"></i>
                        <span>Vui lòng kiểm tra lại thông tin bên dưới.</span>
                    </div>
                @endif

                <form action="{{ route('register') }}" method="POST" novalidate>
                    @csrf

                    {{-- Họ và tên --}}
                    <div class="form-group">
                        <label class="form-label">Họ và tên</label>
                        <div class="input-with-icon">
                            <i class="fa-regular fa-user"></i>
                            <input type="text" name="full_name"
                                class="form-control @error('full_name') is-invalid @enderror" placeholder="Nguyễn Văn A"
                                value="{{ old('full_name') }}" autocomplete="name" autofocus>
                        </div>
                        @error('full_name')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Email --}}
                    <div class="form-group">
                        <label class="form-label">Địa chỉ Email</label>
                        <div class="input-with-icon">
                            <i class="fa-regular fa-envelope"></i>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                placeholder="john@smarttravel.io" value="{{ old('email') }}" autocomplete="email">
                        </div>
                        @error('email')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Password + Confirm --}}
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Mật khẩu</label>
                            <div class="input-with-icon">
                                <i class="fa-solid fa-lock"></i>
                                <input type="password" name="password" id="password"
                                    class="form-control @error('password') is-invalid @enderror" placeholder="••••••••"
                                    autocomplete="new-password">
                            </div>
                            @error('password')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                            {{-- Strength bar --}}
                            <div class="strength-bar" id="strengthBar" style="display:none;">
                                <div class="strength-fill" id="strengthFill"></div>
                            </div>
                            <div class="strength-text" id="strengthText"></div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Xác nhận</label>
                            <div class="input-with-icon">
                                <i class="fa-solid fa-shield"></i>
                                <input type="password" name="password_confirmation" id="password_confirmation"
                                    class="form-control" placeholder="••••••••" autocomplete="new-password">
                            </div>
                            <div class="strength-text" id="confirmText"></div>
                        </div>
                    </div>

                    {{-- Terms --}}
                    <div class="checkbox-group">
                        <input type="checkbox" id="terms" name="terms" required>
                        <label for="terms">
                            Tôi đồng ý với <a href="#">Điều khoản dịch vụ</a> và <a href="#">Chính sách bảo mật</a>
                            điều chỉnh các giao thức du lịch toàn cầu.
                        </label>
                    </div>

                    <button type="submit" class="btn btn-primary" style="width: 100%;">
                        Khởi tạo tài khoản <i class="fa-solid fa-arrow-right"></i>
                    </button>
                </form>

                <div class="divider">HOẶC ĐĂNG KÝ VỚI</div>

                <div class="social-login">
                    <button class="social-btn"><i class="fa-solid fa-id-card"></i> Digital ID</button>
                    <button class="social-btn"><i class="fa-brands fa-google"></i> Google</button>
                </div>

                <p class="text-center mt-8" style="font-size: 14px;">
                    Đã có tài khoản du lịch?
                    <a href="{{ route('login') }}" style="font-weight: 600;">Đăng nhập tại đây</a>
                </p>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        const pwInput = document.getElementById('password');
        const cfInput = document.getElementById('password_confirmation');
        const bar = document.getElementById('strengthBar');
        const fill = document.getElementById('strengthFill');
        const text = document.getElementById('strengthText');
        const cfText = document.getElementById('confirmText');

        const levels = [
            { pct: '25%', color: '#ef4444', label: 'Rất yếu' },
            { pct: '50%', color: '#f97316', label: 'Yếu' },
            { pct: '75%', color: '#eab308', label: 'Khá' },
            { pct: '100%', color: '#22c55e', label: 'Mạnh' },
        ];

        function calcScore(val) {
            let s = 0;
            if (val.length >= 8) s++;
            if (/[A-Z]/.test(val)) s++;
            if (/[0-9]/.test(val)) s++;
            if (/[^A-Za-z0-9]/.test(val)) s++;
            return s;
        }

        pwInput.addEventListener('input', function () {
            const val = this.value;
            if (!val) {
                bar.style.display = 'none';
                text.textContent = '';
                checkConfirm();
                return;
            }
            bar.style.display = 'block';
            const lvl = levels[calcScore(val) - 1] || levels[0];
            fill.style.width = lvl.pct;
            fill.style.backgroundColor = lvl.color;
            text.textContent = 'Độ mạnh: ' + lvl.label;
            text.style.color = lvl.color;
            checkConfirm();
        });

        cfInput.addEventListener('input', checkConfirm);

        function checkConfirm() {
            if (!cfInput.value) { cfText.textContent = ''; return; }
            if (cfInput.value === pwInput.value) {
                cfText.textContent = '✓ Khớp';
                cfText.style.color = '#22c55e';
            } else {
                cfText.textContent = '✗ Chưa khớp';
                cfText.style.color = '#ef4444';
            }
        }
    </script>
@endpush