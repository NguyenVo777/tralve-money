<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="SmartTravel - Nhận diện tiền tệ AI, tỷ giá thời gian thực, bản đồ đổi tiền toàn cầu">
    <title>SmartTravel · @yield('title', 'Khám phá thế giới, đổi tiền thông minh')</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    @stack('styles')
</head>
<body>

<!-- Animated Background -->
<div class="animated-bg"></div>
<canvas id="particles-canvas"></canvas>

@unless(View::hasSection('hide_navbar'))
<nav class="navbar" id="main-navbar">
    <a href="{{ route('home') }}" class="nav-brand">
        <div class="brand-icon"><i class="fa-solid fa-globe"></i></div>
        Smart<span>Travel</span>
    </a>

    <div class="nav-links">
        <a href="{{ route('home') }}" id="nav-home" class="{{ request()->routeIs('home') ? 'active' : '' }}">
            <i class="fa-solid fa-house-chimney"></i> Trang chủ
        </a>
        <a href="{{ route('scan') }}" id="nav-scan" class="{{ request()->routeIs('scan') || request()->routeIs('result') ? 'active' : '' }}">
            <i class="fa-solid fa-camera"></i> Quét tiền
        </a>
        <a href="{{ route('rates') }}" id="nav-rates" class="{{ request()->routeIs('rates') ? 'active' : '' }}">
            <i class="fa-solid fa-chart-line"></i> Tỷ giá
        </a>
        <a href="{{ route('map') }}" id="nav-map" class="{{ request()->routeIs('map') ? 'active' : '' }}">
            <i class="fa-solid fa-map-location-dot"></i> Địa điểm
        </a>
    </div>

    <div class="nav-actions">
        <a href="{{ route('login') }}" class="btn btn-outline btn-sm" id="btn-login">Đăng nhập</a>
        <a href="{{ route('register') }}" class="btn btn-primary btn-sm" id="btn-register">
            <i class="fa-solid fa-user-plus"></i> Đăng ký
        </a>
    </div>
</nav>
@endunless

@yield('content')

<!-- Toast Container -->
<div class="toast-container" id="toast-container"></div>

@stack('scripts')

<script>
// ===== PARTICLES =====
(function() {
    const canvas = document.getElementById('particles-canvas');
    if (!canvas) return;
    const ctx = canvas.getContext('2d');
    let particles = [];
    let W, H;

    function resize() {
        W = canvas.width = window.innerWidth;
        H = canvas.height = window.innerHeight;
    }
    resize();
    window.addEventListener('resize', resize);

    function randBetween(a, b) { return a + Math.random() * (b - a); }

    class Particle {
        constructor() { this.reset(); }
        reset() {
            this.x = randBetween(0, W);
            this.y = randBetween(0, H);
            this.r = randBetween(0.5, 2);
            this.vx = randBetween(-0.3, 0.3);
            this.vy = randBetween(-0.4, -0.1);
            this.alpha = randBetween(0.1, 0.6);
            this.color = Math.random() > 0.5 ? '0,212,255' : '124,111,255';
        }
        update() {
            this.x += this.vx;
            this.y += this.vy;
            if (this.y < -5 || this.x < -5 || this.x > W + 5) this.reset();
        }
        draw() {
            ctx.beginPath();
            ctx.arc(this.x, this.y, this.r, 0, Math.PI * 2);
            ctx.fillStyle = `rgba(${this.color},${this.alpha})`;
            ctx.fill();
        }
    }

    for (let i = 0; i < 100; i++) particles.push(new Particle());

    function loop() {
        ctx.clearRect(0, 0, W, H);
        particles.forEach(p => { p.update(); p.draw(); });
        requestAnimationFrame(loop);
    }
    loop();
})();

// ===== TOAST SYSTEM =====
function showToast(message, type = 'info', duration = 3000) {
    const container = document.getElementById('toast-container');
    const icons = { success: 'fa-circle-check', error: 'fa-circle-xmark', info: 'fa-circle-info' };
    const colors = { success: 'var(--accent)', error: 'var(--danger)', info: 'var(--primary)' };
    const toast = document.createElement('div');
    toast.className = `toast ${type}`;
    toast.innerHTML = `<i class="fa-solid ${icons[type]}" style="color:${colors[type]};font-size:18px;"></i><span>${message}</span>`;
    container.appendChild(toast);
    setTimeout(() => {
        toast.style.animation = 'toastIn 0.3s ease reverse';
        setTimeout(() => toast.remove(), 300);
    }, duration);
}
</script>
</body>
</html>
