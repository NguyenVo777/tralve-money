@extends('layouts.app')
@section('title', 'Trang chủ')

@push('styles')
<style>
/* HERO */
.hero-section {
    min-height: calc(100vh - var(--navbar-height));
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    text-align: center;
    padding: 60px 5% 80px;
    position: relative;
    overflow: hidden;
}
.hero-section::before {
    content: '';
    position: absolute;
    inset: 0;
    background:
        radial-gradient(ellipse 70% 50% at 50% 30%, rgba(0,212,255,0.12) 0%, transparent 60%),
        radial-gradient(ellipse 40% 40% at 80% 70%, rgba(124,111,255,0.1) 0%, transparent 50%);
    pointer-events: none;
}
.hero-badge {
    display: inline-flex; align-items: center; gap: 8px;
    padding: 6px 16px;
    background: rgba(0,212,255,0.08);
    border: 1px solid rgba(0,212,255,0.25);
    border-radius: 30px;
    font-size: 12px; font-weight: 600;
    color: var(--primary);
    text-transform: uppercase; letter-spacing: 1.5px;
    margin-bottom: 28px;
    animation: fadeInDown 0.6s ease;
}
.hero-badge .dot {
    width: 6px; height: 6px; border-radius: 50%;
    background: var(--primary);
    animation: pulse-dot 2s ease-in-out infinite;
}
@keyframes pulse-dot {
    0%,100%{opacity:1;transform:scale(1)}
    50%{opacity:0.4;transform:scale(1.4)}
}
.hero-title {
    font-size: clamp(40px, 7vw, 80px);
    font-weight: 800;
    line-height: 1.1;
    letter-spacing: -2px;
    margin-bottom: 24px;
    animation: fadeInDown 0.7s ease 0.1s both;
}
.hero-title .line2 { color: var(--primary); }
.hero-subtitle {
    font-size: 18px; color: var(--text-muted);
    max-width: 580px; line-height: 1.7;
    margin-bottom: 48px;
    animation: fadeInDown 0.7s ease 0.2s both;
}
@keyframes fadeInDown {
    from { opacity:0; transform: translateY(-20px); }
    to { opacity:1; transform: translateY(0); }
}

/* SEARCH BAR */
.hero-search {
    background: rgba(13,21,38,0.8);
    backdrop-filter: blur(20px);
    border: 1px solid var(--border-light);
    border-radius: 60px;
    padding: 8px 8px 8px 28px;
    display: inline-flex;
    align-items: center;
    gap: 12px;
    max-width: 780px;
    width: 100%;
    box-shadow: 0 8px 40px rgba(0,0,0,0.4), 0 0 0 1px rgba(0,212,255,0.1);
    animation: fadeInDown 0.7s ease 0.3s both;
    transition: box-shadow 0.3s;
}
.hero-search:focus-within {
    box-shadow: 0 8px 40px rgba(0,0,0,0.4), 0 0 0 2px rgba(0,212,255,0.35);
}
.hero-search .field {
    display: flex; align-items: center; gap: 10px;
    flex: 1;
}
.hero-search .field i { color: var(--primary); font-size: 16px; flex-shrink: 0; }
.hero-search .field input {
    background: transparent; border: none; outline: none;
    color: var(--text-main); font-size: 15px; width: 100%;
}
.hero-search .field input::placeholder { color: var(--text-muted); }
.hero-search .sep {
    width: 1px; height: 30px;
    background: var(--border-light);
    flex-shrink: 0;
}
.hero-search .search-btn {
    background: linear-gradient(135deg, var(--secondary), var(--primary));
    color: #fff; border: none; cursor: pointer;
    padding: 14px 32px;
    border-radius: 50px;
    font-size: 15px; font-weight: 600;
    display: flex; align-items: center; gap: 8px;
    transition: 0.3s;
    white-space: nowrap;
}
.hero-search .search-btn:hover {
    transform: scale(1.04);
    box-shadow: 0 4px 20px var(--primary-glow);
}

/* HERO STATS */
.hero-stats {
    display: flex; gap: 40px;
    margin-top: 60px;
    animation: fadeInDown 0.7s ease 0.4s both;
}
.hero-stat { text-align: center; }
.hero-stat .num {
    font-size: 28px; font-weight: 800;
    background: linear-gradient(135deg, #fff, var(--primary));
    -webkit-background-clip: text; -webkit-text-fill-color: transparent;
}
.hero-stat .lbl { font-size: 12px; color: var(--text-muted); margin-top: 4px; }
.stat-divider {
    width: 1px; background: var(--border); align-self: stretch;
}

/* FEATURES */
.features-section {
    padding: 100px 5%;
    max-width: 1300px;
    margin: 0 auto;
    width: 100%;
}
.features-header { text-align: center; margin-bottom: 64px; }
.features-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 24px;
}
.feature-card {
    background: rgba(13,21,38,0.6);
    border: 1px solid var(--border-light);
    border-radius: 20px;
    padding: 32px;
    transition: 0.35s;
    cursor: pointer;
    position: relative;
    overflow: hidden;
}
.feature-card::before {
    content: '';
    position: absolute;
    top: 0; left: 0; right: 0;
    height: 2px;
    background: linear-gradient(90deg, var(--secondary), var(--primary));
    transform: scaleX(0);
    transition: 0.35s;
}
.feature-card:hover::before { transform: scaleX(1); }
.feature-card:hover {
    transform: translateY(-8px);
    border-color: rgba(0,212,255,0.25);
    box-shadow: 0 20px 50px rgba(0,0,0,0.4), 0 0 30px rgba(0,212,255,0.08);
}
.feature-icon {
    width: 56px; height: 56px;
    border-radius: 16px;
    display: flex; align-items: center; justify-content: center;
    font-size: 24px;
    margin-bottom: 24px;
}
.feature-icon.blue { background: rgba(0,212,255,0.1); color: var(--primary); }
.feature-icon.purple { background: rgba(124,111,255,0.1); color: var(--secondary); }
.feature-icon.green { background: rgba(0,229,195,0.1); color: var(--accent); }
.feature-icon.gold { background: rgba(255,215,0,0.1); color: var(--gold); }
.feature-card h3 { font-size: 20px; font-weight: 700; margin-bottom: 12px; }
.feature-card p { color: var(--text-muted); font-size: 14px; line-height: 1.65; }
.feature-link {
    display: inline-flex; align-items: center; gap: 6px;
    margin-top: 20px; font-size: 13px; font-weight: 600;
    color: var(--primary);
}
.feature-link:hover { gap: 10px; }

/* LIVE RATES BAND */
.rates-band {
    background: rgba(13,21,38,0.6);
    border-top: 1px solid var(--border-light);
    border-bottom: 1px solid var(--border-light);
    padding: 20px 0;
    overflow: hidden;
    position: relative;
}
.rates-ticker {
    display: flex; gap: 48px;
    animation: ticker 30s linear infinite;
    white-space: nowrap;
}
.rates-ticker:hover { animation-play-state: paused; }
@keyframes ticker {
    from { transform: translateX(0); }
    to { transform: translateX(-50%); }
}
.ticker-item {
    display: inline-flex; align-items: center; gap: 10px;
    font-size: 14px;
}
.ticker-item .pair { font-weight: 600; color: var(--text-main); }
.ticker-item .rate { color: var(--text-muted); }
.ticker-item .change.up { color: var(--accent); }
.ticker-item .change.down { color: var(--danger); }

/* HOW IT WORKS */
.howto-section {
    padding: 100px 5%;
    max-width: 1100px;
    margin: 0 auto;
    width: 100%;
}
.howto-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 32px;
    margin-top: 60px;
    counter-reset: steps;
}
.howto-card {
    text-align: center;
    counter-increment: steps;
    position: relative;
}
.howto-card::before {
    content: counter(steps);
    position: absolute;
    top: 0; left: 50%;
    transform: translate(-50%, -50%);
    width: 32px; height: 32px;
    background: linear-gradient(135deg, var(--secondary), var(--primary));
    border-radius: 50%;
    font-size: 13px; font-weight: 700;
    display: flex; align-items: center; justify-content: center;
    color: #fff;
}
.howto-icon-wrap {
    width: 80px; height: 80px;
    border-radius: 24px;
    background: var(--bg-surface);
    border: 1px solid var(--border);
    display: flex; align-items: center; justify-content: center;
    font-size: 32px; color: var(--primary);
    margin: 0 auto 20px;
    transition: 0.3s;
}
.howto-card:hover .howto-icon-wrap {
    background: rgba(0,212,255,0.08);
    border-color: var(--primary);
    box-shadow: 0 0 20px var(--primary-glow);
}
.howto-card h4 { font-size: 18px; font-weight: 700; margin-bottom: 10px; }
.howto-card p { font-size: 14px; color: var(--text-muted); line-height: 1.6; }

/* CTA */
.cta-section {
    padding: 80px 5%;
    text-align: center;
}
.cta-box {
    max-width: 800px;
    margin: 0 auto;
    background: linear-gradient(135deg, rgba(124,111,255,0.12), rgba(0,212,255,0.08));
    border: 1px solid rgba(0,212,255,0.2);
    border-radius: 28px;
    padding: 64px 48px;
    position: relative;
    overflow: hidden;
}
.cta-box::before {
    content: '';
    position: absolute;
    top: -50%; left: 50%;
    transform: translateX(-50%);
    width: 300px; height: 300px;
    background: radial-gradient(circle, rgba(0,212,255,0.12), transparent);
    pointer-events: none;
}
.cta-btns { display: flex; gap: 16px; justify-content: center; margin-top: 32px; flex-wrap: wrap; }
</style>
@endpush

@section('content')
<!-- HERO -->
<section class="hero-section">
    <div class="hero-badge">
        <span class="dot"></span>
        AI nhận diện tiền tệ · Thời gian thực
    </div>
    <h1 class="hero-title">
        Khám phá thế giới<br>
        <span class="line2">đổi tiền thông minh</span>
    </h1>
    <p class="hero-subtitle">
        Trải nghiệm công nghệ AI nhận diện tiền tệ hàng đầu, tỷ giá cập nhật theo giây và bản đồ hàng nghìn điểm đổi tiền toàn cầu.
    </p>

    <form class="hero-search" action="{{ route('map') }}" method="GET" id="hero-search-form">
        <div class="field">
            <i class="fa-solid fa-money-bill-wave"></i>
            <input type="text" name="currency" placeholder="Loại tiền tệ bạn muốn đổi..." id="hero-currency">
        </div>
        <div class="sep"></div>
        <div class="field">
            <i class="fa-solid fa-location-dot"></i>
            <input type="text" name="location" placeholder="Địa điểm tìm kiếm..." id="hero-location">
        </div>
        <button type="submit" class="search-btn">
            <i class="fa-solid fa-magnifying-glass"></i> Tìm kiếm
        </button>
    </form>

    <div class="hero-stats">
        <div class="hero-stat">
            <div class="num" id="stat-currencies">170+</div>
            <div class="lbl">Loại tiền tệ</div>
        </div>
        <div class="stat-divider"></div>
        <div class="hero-stat">
            <div class="num" id="stat-locations">5,000+</div>
            <div class="lbl">Địa điểm đổi tiền</div>
        </div>
        <div class="stat-divider"></div>
        <div class="hero-stat">
            <div class="num" id="stat-accuracy">99.9%</div>
            <div class="lbl">Độ chính xác AI</div>
        </div>
        <div class="stat-divider"></div>
        <div class="hero-stat">
            <div class="num" id="stat-users">200k+</div>
            <div class="lbl">Người dùng</div>
        </div>
    </div>
</section>

<!-- LIVE RATES TICKER -->
<div class="rates-band">
    <div class="rates-ticker" id="rates-ticker">
        <!-- Items duplicated for infinite scroll -->
        <span class="ticker-item"><span class="pair">USD/VND</span><span class="rate">25,410</span><span class="change up">▲ +0.12%</span></span>
        <span class="ticker-item"><span class="pair">EUR/VND</span><span class="rate">27,650</span><span class="change down">▼ -0.08%</span></span>
        <span class="ticker-item"><span class="pair">JPY/VND</span><span class="rate">165.4</span><span class="change up">▲ +0.31%</span></span>
        <span class="ticker-item"><span class="pair">GBP/VND</span><span class="rate">31,200</span><span class="change up">▲ +0.05%</span></span>
        <span class="ticker-item"><span class="pair">KRW/VND</span><span class="rate">18.2</span><span class="change down">▼ -0.22%</span></span>
        <span class="ticker-item"><span class="pair">THB/VND</span><span class="rate">705</span><span class="change up">▲ +0.18%</span></span>
        <span class="ticker-item"><span class="pair">SGD/VND</span><span class="rate">18,820</span><span class="change up">▲ +0.09%</span></span>
        <span class="ticker-item"><span class="pair">AUD/VND</span><span class="rate">16,450</span><span class="change down">▼ -0.15%</span></span>
        <!-- Duplicate for seamless loop -->
        <span class="ticker-item"><span class="pair">USD/VND</span><span class="rate">25,410</span><span class="change up">▲ +0.12%</span></span>
        <span class="ticker-item"><span class="pair">EUR/VND</span><span class="rate">27,650</span><span class="change down">▼ -0.08%</span></span>
        <span class="ticker-item"><span class="pair">JPY/VND</span><span class="rate">165.4</span><span class="change up">▲ +0.31%</span></span>
        <span class="ticker-item"><span class="pair">GBP/VND</span><span class="rate">31,200</span><span class="change up">▲ +0.05%</span></span>
        <span class="ticker-item"><span class="pair">KRW/VND</span><span class="rate">18.2</span><span class="change down">▼ -0.22%</span></span>
        <span class="ticker-item"><span class="pair">THB/VND</span><span class="rate">705</span><span class="change up">▲ +0.18%</span></span>
        <span class="ticker-item"><span class="pair">SGD/VND</span><span class="rate">18,820</span><span class="change up">▲ +0.09%</span></span>
        <span class="ticker-item"><span class="pair">AUD/VND</span><span class="rate">16,450</span><span class="change down">▼ -0.15%</span></span>
    </div>
</div>

<!-- FEATURES -->
<section class="features-section">
    <div class="features-header">
        <div class="section-label">Tính năng</div>
        <h2 class="section-title">Tất cả công cụ bạn cần</h2>
        <p class="section-desc" style="margin:0 auto;">Từ nhận diện tiền tệ bằng AI đến bản đồ điểm đổi tiền — SmartTravel có mọi thứ cho chuyến đi của bạn.</p>
    </div>

    <div class="features-grid">
        <div class="feature-card" onclick="window.location='{{ route('scan') }}'">
            <div class="feature-icon blue"><i class="fa-solid fa-camera-retro"></i></div>
            <h3>Quét AI Siêu Tốc</h3>
            <p>Nhận diện tức thì mọi loại tiền tệ thế giới bằng camera với độ chính xác 99.9% nhờ mô hình deep learning tiên tiến.</p>
            <a href="{{ route('scan') }}" class="feature-link">Thử ngay <i class="fa-solid fa-arrow-right"></i></a>
        </div>

        <div class="feature-card" onclick="window.location='{{ route('rates') }}'">
            <div class="feature-icon purple"><i class="fa-solid fa-chart-line"></i></div>
            <h3>Tỷ giá thời gian thực</h3>
            <p>Theo dõi biến động 170+ cặp tiền tệ theo giây, với biểu đồ lịch sử và dự báo thông minh từ AI.</p>
            <a href="{{ route('rates') }}" class="feature-link">Xem tỷ giá <i class="fa-solid fa-arrow-right"></i></a>
        </div>

        <div class="feature-card" onclick="window.location='{{ route('map') }}'">
            <div class="feature-icon green"><i class="fa-solid fa-map-location-dot"></i></div>
            <h3>Bản đồ địa điểm</h3>
            <p>Tìm ngay hàng nghìn quầy đổi ngoại tệ uy tín gần bạn nhất, với tỷ giá và đánh giá từ cộng đồng.</p>
            <a href="{{ route('map') }}" class="feature-link">Khám phá <i class="fa-solid fa-arrow-right"></i></a>
        </div>

        <div class="feature-card">
            <div class="feature-icon gold"><i class="fa-solid fa-shield-halved"></i></div>
            <h3>Bảo mật tuyệt đối</h3>
            <p>Dữ liệu giao dịch được mã hóa end-to-end theo chuẩn AES-256, bảo vệ tuyệt đối mọi thông tin của bạn.</p>
            <span class="badge badge-success" style="margin-top:20px;">Đang bảo vệ</span>
        </div>
    </div>
</section>

<!-- HOW IT WORKS -->
<section class="howto-section">
    <div class="text-center">
        <div class="section-label">Cách hoạt động</div>
        <h2 class="section-title">Chỉ 3 bước đơn giản</h2>
        <p class="section-desc" style="margin:0 auto;">Bắt đầu trải nghiệm SmartTravel trong vài giây.</p>
    </div>

    <div class="howto-grid">
        <div class="howto-card">
            <div class="howto-icon-wrap" style="margin-top:20px;">
                <i class="fa-solid fa-camera"></i>
            </div>
            <h4>Chụp ảnh tiền</h4>
            <p>Dùng camera điện thoại hoặc tải ảnh tờ tiền lên hệ thống.</p>
        </div>
        <div class="howto-card">
            <div class="howto-icon-wrap" style="margin-top:20px;">
                <i class="fa-solid fa-microchip-ai"></i>
            </div>
            <h4>AI nhận diện</h4>
            <p>Hệ thống AI tự động xác định mệnh giá, quốc gia và tỷ giá hiện tại.</p>
        </div>
        <div class="howto-card">
            <div class="howto-icon-wrap" style="margin-top:20px;">
                <i class="fa-solid fa-map-pin"></i>
            </div>
            <h4>Tìm điểm đổi</h4>
            <p>Xem ngay danh sách điểm đổi tiền tốt nhất gần vị trí của bạn.</p>
        </div>
        <div class="howto-card">
            <div class="howto-icon-wrap" style="margin-top:20px;">
                <i class="fa-solid fa-circle-check"></i>
            </div>
            <h4>Tiết kiệm tối đa</h4>
            <p>So sánh tỷ giá và chọn điểm đổi tiền tối ưu nhất cho bạn.</p>
        </div>
    </div>
</section>

<!-- CTA -->
<section class="cta-section">
    <div class="cta-box">
        <div class="section-label">Bắt đầu ngay hôm nay</div>
        <h2 class="section-title" style="font-size:40px;">Sẵn sàng khám phá thế giới?</h2>
        <p class="section-desc" style="margin:0 auto;">Tham gia cùng 200,000+ du khách đang sử dụng SmartTravel để tiết kiệm hàng triệu đồng mỗi chuyến đi.</p>
        <div class="cta-btns">
            <a href="{{ route('scan') }}" class="btn btn-primary btn-lg">
                <i class="fa-solid fa-camera"></i> Quét tiền ngay
            </a>
            <a href="{{ route('register') }}" class="btn btn-outline btn-lg">
                <i class="fa-solid fa-user-plus"></i> Tạo tài khoản miễn phí
            </a>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
// Animate counter numbers
function animateCounter(el, target, suffix='') {
    const start = 0;
    const duration = 1500;
    const step = target / (duration / 16);
    let current = start;
    const timer = setInterval(() => {
        current = Math.min(current + step, target);
        el.textContent = Math.floor(current).toLocaleString() + suffix;
        if (current >= target) clearInterval(timer);
    }, 16);
}

const observer = new IntersectionObserver((entries) => {
    entries.forEach(e => {
        if (e.isIntersecting) {
            animateCounter(document.getElementById('stat-currencies'), 170, '+');
            animateCounter(document.getElementById('stat-locations'), 5000, '+');
            animateCounter(document.getElementById('stat-users'), 200, 'k+');
            observer.disconnect();
        }
    });
}, { threshold: 0.3 });
const statsEl = document.querySelector('.hero-stats');
if (statsEl) observer.observe(statsEl);
</script>
@endpush
