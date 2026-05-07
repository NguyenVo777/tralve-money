@extends('layouts.app')

@section('title', 'Kết quả Quét')
@section('hide_navbar', true)

@push('styles')
<style>
    .result-page {
        min-height: 100vh;
        background-color: var(--bg-color);
        padding: 80px 5% 100px;
    }
    
    .scan-header {
        position: fixed;
        top: 0; left: 0; width: 100%;
        padding: 20px 5%;
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: rgba(11, 17, 32, 0.9);
        backdrop-filter: blur(10px);
        z-index: 100;
        border-bottom: 1px solid var(--border);
    }
    
    .grid-container {
        display: grid;
        grid-template-columns: 1fr 1.5fr;
        gap: 24px;
        max-width: 1200px;
        margin: 0 auto 24px;
    }
    
    .card-title {
        font-size: 24px;
        font-weight: 600;
        margin-bottom: 24px;
    }
    
    /* Result Card */
    .result-card {
        text-align: center;
        display: flex;
        flex-direction: column;
        align-items: center;
    }
    .currency-box {
        width: 100%;
        max-width: 250px;
        aspect-ratio: 16/10;
        background: rgba(255,255,255,0.05);
        border: 1px solid var(--border);
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 64px;
        font-weight: 800;
        color: #94a3b8;
        margin: 20px 0;
        box-shadow: inset 0 0 50px rgba(0,0,0,0.5);
    }
    .action-buttons {
        display: flex;
        gap: 16px;
        width: 100%;
        margin-top: 32px;
    }
    
    /* Chart */
    .chart-info {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 16px;
        margin-top: 24px;
    }
    .info-item {
        background: rgba(0,0,0,0.2);
        padding: 16px;
        border-radius: 12px;
        border: 1px solid var(--border);
    }
    .info-item.highlight {
        background: rgba(6, 182, 212, 0.1);
        border-color: var(--primary);
    }
    
    /* Itinerary Section */
    .itinerary-section {
        max-width: 1200px;
        margin: 0 auto;
    }
    .itinerary-grid {
        display: grid;
        grid-template-columns: 1fr 2fr;
        gap: 24px;
        margin-top: 24px;
    }
    .destination-card {
        background: url('https://images.unsplash.com/photo-1496442226666-8d4d0e62e6e9?q=80&w=1000&auto=format&fit=crop') center/cover;
        border-radius: 16px;
        height: 300px;
        position: relative;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        justify-content: flex-end;
        padding: 24px;
    }
    .destination-card::before {
        content: '';
        position: absolute;
        bottom: 0; left: 0; width: 100%; height: 70%;
        background: linear-gradient(to top, rgba(11,17,32,0.95), transparent);
    }
    .dest-content {
        position: relative;
        z-index: 1;
    }
    .dest-badge {
        position: absolute;
        top: 24px; right: 24px;
        background: rgba(17, 24, 39, 0.8);
        backdrop-filter: blur(10px);
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }
    
    /* Timeline styling */
    .itinerary-timeline {
        position: relative;
        padding-left: 30px;
        margin-top: 24px;
    }
    .itinerary-timeline::before {
        content: '';
        position: absolute;
        left: 7px; top: 0; bottom: 0;
        width: 2px;
        background: var(--border);
    }
    .timeline-node {
        position: relative;
        margin-bottom: 32px;
    }
    .timeline-node::before {
        content: '';
        position: absolute;
        left: -30px;
        top: 4px;
        width: 16px;
        height: 16px;
        border-radius: 50%;
        background: var(--bg-surface);
        border: 2px solid var(--text-muted);
        z-index: 2;
    }
    .timeline-node.active::before {
        border-color: var(--primary);
        background: var(--primary);
        box-shadow: 0 0 10px var(--primary-glow);
    }
    .timeline-node.active::after {
        content: '';
        position: absolute;
        left: -26px;
        top: 8px;
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: #0b1120;
        z-index: 3;
    }
    
    @media (max-width: 900px) {
        .grid-container, .itinerary-grid {
            grid-template-columns: 1fr;
        }
        .chart-info {
            grid-template-columns: 1fr;
        }
    }
    
    .bottom-nav {
        position: fixed;
        bottom: 0;
        left: 0;
        width: 100%;
        background: rgba(11, 17, 32, 0.9);
        backdrop-filter: blur(10px);
        border-top: 1px solid var(--border);
        display: flex;
        justify-content: space-around;
        padding: 16px 0;
        z-index: 100;
    }
    .nav-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 6px;
        color: var(--text-muted);
        text-decoration: none;
        font-size: 10px;
        text-transform: uppercase;
        letter-spacing: 1px;
        transition: 0.3s;
    }
    .nav-item i {
        font-size: 20px;
    }
    .nav-item.active {
        color: var(--primary);
    }
</style>
@endpush

@section('content')
<header class="scan-header">
    <a href="{{ route('home') }}" class="nav-brand">Smart<span>Travel</span></a>
    <div style="display: flex; gap: 16px; color: var(--text-muted);">
        <i class="fa-solid fa-clock-rotate-left" style="cursor: pointer;"></i>
        <i class="fa-regular fa-circle-user" style="cursor: pointer;"></i>
    </div>
</header>

<div class="result-page">
    <div class="grid-container">
        <!-- Result Card -->
        <div class="glass-card result-card">
            <div style="width: 100%; text-align: left;">
                <div style="font-size: 12px; color: var(--primary); text-transform: uppercase; letter-spacing: 1px; margin-bottom: 8px;">KẾT QUẢ QUÉT</div>
                <h2 class="card-title" style="margin: 0;">Nhận diện Tiền tệ</h2>
            </div>
            
            <div class="currency-box">
                $ USD
            </div>
            
            <h3 style="font-size: 28px; margin-bottom: 4px;">Đô la Mỹ</h3>
            <p style="color: var(--text-muted);">Đô la Mỹ</p>
            
            <div class="action-buttons">
                <button class="btn btn-primary" style="flex: 1;"><i class="fa-solid fa-bookmark"></i> Lưu kết quả</button>
                <button class="btn btn-outline" style="flex: 1;"><i class="fa-solid fa-share-nodes"></i> Chia sẻ</button>
            </div>
        </div>
        
        <!-- Chart Card -->
        <div class="glass-card">
            <div class="flex justify-between items-center mb-6">
                <h2 class="card-title" style="margin: 0;">Biểu đồ tỷ giá</h2>
                <div style="font-size: 12px; color: var(--text-muted);">7 NGÀY QUA</div>
            </div>
            
            <!-- SVG Chart -->
            <svg viewBox="0 0 500 150" style="width: 100%; height: 200px; overflow: visible; margin: 20px 0;">
                <!-- Grid Lines -->
                <line x1="0" y1="150" x2="500" y2="150" stroke="var(--border)" stroke-width="1" />
                <!-- Path -->
                <path d="M0,120 C50,110 100,140 150,130 C200,120 250,50 300,80 C350,110 400,130 450,20 C480,-10 500,50 500,50" fill="none" stroke="var(--primary)" stroke-width="4" stroke-linecap="round" />
                <!-- Data Points -->
                <circle cx="150" cy="130" r="5" fill="var(--primary)" stroke="var(--bg-card)" stroke-width="2" />
                <circle cx="300" cy="80" r="5" fill="var(--primary)" stroke="var(--bg-card)" stroke-width="2" />
                <circle cx="450" cy="20" r="5" fill="var(--secondary)" stroke="var(--bg-card)" stroke-width="2" />
                
                <!-- X Axis Labels -->
                <text x="0" y="170" fill="var(--text-muted)" font-size="10">T2</text>
                <text x="100" y="170" fill="var(--text-muted)" font-size="10">T3</text>
                <text x="200" y="170" fill="var(--text-muted)" font-size="10">T4</text>
                <text x="300" y="170" fill="var(--text-muted)" font-size="10">T5</text>
                <text x="400" y="170" fill="var(--text-muted)" font-size="10">T6</text>
                <text x="450" y="170" fill="var(--text-muted)" font-size="10">T7</text>
                <text x="500" y="170" fill="var(--text-muted)" font-size="10">CN</text>
            </svg>
            
            <div class="chart-info">
                <div class="info-item">
                    <div style="font-size: 12px; color: var(--text-muted); margin-bottom: 8px;">Giá trị hiện tại</div>
                    <div style="font-size: 20px; font-weight: 700; color: var(--primary); margin-bottom: 4px;">24.520 VND</div>
                    <div style="font-size: 10px; color: var(--danger);"><i class="fa-solid fa-arrow-trend-down"></i> -0.15%</div>
                </div>
                <div class="info-item">
                    <div style="font-size: 12px; color: var(--text-muted); margin-bottom: 8px;">Xu hướng</div>
                    <div style="font-size: 20px; font-weight: 700; margin-bottom: 4px;">Tăng nhẹ</div>
                    <div style="font-size: 10px; color: var(--primary);">Dựa trên 30 ngày</div>
                </div>
                <div class="info-item highlight">
                    <div style="font-size: 12px; margin-bottom: 8px;">Khuyên dùng</div>
                    <div style="font-size: 20px; font-weight: 700; margin-bottom: 4px;">Nên Đổi</div>
                    <div style="font-size: 10px;">TỶ GIÁ TỐT NHẤT</div>
                    <i class="fa-solid fa-thumbs-up" style="position: absolute; bottom: 16px; right: 16px; opacity: 0.2; font-size: 32px;"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="itinerary-section">
        <h2 style="font-size: 20px; display: flex; align-items: center; gap: 8px;">
            <i class="fa-regular fa-compass" style="color: var(--primary);"></i> Gợi ý lịch trình
        </h2>
        
        <div class="itinerary-grid">
            <div class="destination-card">
                <div class="dest-badge">USD Phổ biến</div>
                <div class="dest-content">
                    <div class="flex justify-between items-end mb-2">
                        <h3 style="font-size: 28px;">New York</h3>
                        <div style="font-size: 20px; font-weight: 700; color: var(--primary);">$2,450</div>
                    </div>
                    <p style="font-size: 14px; color: var(--text-muted); margin-bottom: 20px; line-height: 1.5;">Khám phá thành phố không bao giờ ngủ với những trải nghiệm sang trọng và hiện đại.</p>
                    <div class="flex justify-between items-center">
                        <div style="display: flex;">
                            <div style="width: 24px; height: 24px; border-radius: 50%; background: #334155; border: 2px solid var(--bg-surface); display: flex; align-items: center; justify-content: center; font-size: 10px; margin-right: -8px;">JD</div>
                            <div style="width: 24px; height: 24px; border-radius: 50%; background: #818cf8; border: 2px solid var(--bg-surface); display: flex; align-items: center; justify-content: center; font-size: 10px; margin-right: -8px;">SK</div>
                            <div style="width: 24px; height: 24px; border-radius: 50%; background: var(--primary); border: 2px solid var(--bg-surface); display: flex; align-items: center; justify-content: center; font-size: 10px; color: #000;">+5</div>
                        </div>
                        <a href="#" style="font-size: 12px; font-weight: 600; text-transform: uppercase; letter-spacing: 1px;">Chi tiết <i class="fa-solid fa-arrow-right"></i></a>
                    </div>
                </div>
            </div>
            
            <div class="glass-card">
                <div class="flex justify-between items-center">
                    <h3 style="font-size: 20px;">Lịch trình đề xuất</h3>
                    <div style="display: flex; gap: 8px;">
                        <span style="padding: 6px 12px; border-radius: 20px; background: rgba(255,255,255,0.1); font-size: 12px;">7 Ngày</span>
                        <span style="padding: 6px 12px; border-radius: 20px; background: rgba(255,255,255,0.1); font-size: 12px;">Cao cấp</span>
                    </div>
                </div>
                
                <div class="itinerary-timeline">
                    <div class="timeline-node active">
                        <div style="font-size: 12px; color: var(--primary); font-weight: 600; margin-bottom: 4px;">NGÀY 1-2</div>
                        <h4 style="font-size: 16px; margin-bottom: 8px;">Trung tâm Manhattan</h4>
                        <p style="font-size: 14px; color: var(--text-muted);">Check-in khách sạn 5 sao, tham quan Time Square và Broadway.</p>
                    </div>
                    
                    <div class="timeline-node">
                        <div style="font-size: 12px; color: var(--text-muted); font-weight: 600; margin-bottom: 4px;">NGÀY 3-5</div>
                        <h4 style="font-size: 16px; margin-bottom: 8px;">Văn hóa & Nghệ thuật</h4>
                        <p style="font-size: 14px; color: var(--text-muted);">Tham quan bảo tàng MET, dạo quanh Central Park và trải nghiệm ẩm thực Michelin.</p>
                    </div>
                    
                    <div class="timeline-node">
                        <div style="font-size: 12px; color: var(--text-muted); font-weight: 600; margin-bottom: 4px;">NGÀY 6-7</div>
                        <h4 style="font-size: 16px; margin-bottom: 8px;">Mua sắm & Nghỉ ngơi</h4>
                        <p style="font-size: 14px; color: var(--text-muted);">Fifth Avenue shopping và bay về từ JFK.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<nav class="bottom-nav">
    <a href="{{ route('home') }}" class="nav-item">
        <i class="fa-solid fa-house"></i>
        <span>HOME</span>
    </a>
    <a href="{{ route('scan') }}" class="nav-item active">
        <i class="fa-solid fa-expand"></i>
        <span>SCAN</span>
    </a>
    <a href="{{ route('rates') }}" class="nav-item">
        <i class="fa-solid fa-chart-line"></i>
        <span>RATES</span>
    </a>
    <a href="#" class="nav-item">
        <i class="fa-solid fa-clock-rotate-left"></i>
        <span>HISTORY</span>
    </a>
</nav>
@endsection
