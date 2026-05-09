@extends('layouts.app')
@section('title', 'Quét Tiền Tệ AI')

@push('styles')
    <style>
        /* =========================================
                               PAGE - Tối ưu độ rộng để tập trung hơn
                            ========================================= */
        .scan-page {
            width: 100%;
            max-width: 1100px;
            /* Thu nhỏ từ 1450px để giao diện gọn hơn */
            margin: 0 auto;
            padding: 25px clamp(15px, 2vw, 30px) 60px;
            min-height: calc(100vh - 80px);
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        /* =========================================
                               HEADER - Làm gọn khoảng cách
                            ========================================= */
        .scan-page-header {
            text-align: center;
            margin-bottom: 25px;
            max-width: 700px;
            margin-inline: auto;
        }

        .scan-page-header h1 {
            font-size: clamp(32px, 3.5vw, 48px);
            /* Chữ tiêu đề vừa phải hơn */
            font-weight: 800;
            margin-bottom: 8px;
            letter-spacing: -1px;
            line-height: 1.1;
        }

        .scan-page-header p {
            color: var(--text-muted);
            font-size: 14px;
            max-width: 550px;
            margin: 0 auto;
            line-height: 1.6;
        }

        /* =========================================
                               LAYOUT - Tỷ lệ 1fr : 320px
                            ========================================= */
        .scan-layout {
            display: grid;
            grid-template-columns: minmax(0, 1fr) 320px;
            /* Thu nhỏ sidebar */
            gap: 20px;
            align-items: start;
        }

        /* =========================================
                               SCANNER BOX & VIEWPORT - Giảm chiều cao
                            ========================================= */
        .scanner-box {
            background: linear-gradient(180deg, rgba(13, 21, 38, 0.96), rgba(7, 12, 24, 0.94));
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 20px;
            overflow: hidden;
            position: relative;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.35);
        }

        .scanner-viewport {
            width: 100%;
            min-height: 420px;
            /* Giảm từ 540px xuống để vừa màn hình laptop */
            background: #000;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        #camera-feed,
        #preview-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            position: absolute;
            inset: 0;
        }

        #preview-img {
            display: none;
        }

        /* =========================================
                               OVERLAY & FRAME - Cân đối lại
                            ========================================= */
        .scan-overlay {
            position: absolute;
            inset: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            pointer-events: none;
        }

        .scan-frame {
            width: 65%;
            /* Thu nhỏ vùng lấy nét */
            height: 50%;
            position: relative;
            border-radius: 16px;
            background: linear-gradient(rgba(0, 212, 255, 0.03), rgba(0, 212, 255, 0.01));
            backdrop-filter: blur(2px);
        }

        .scan-frame::before,
        .scan-frame::after,
        .sf-inner::before,
        .sf-inner::after {
            content: '';
            position: absolute;
            width: 30px;
            height: 30px;
            border-color: var(--primary);
            border-style: solid;
            border-radius: 4px;
        }

        .scan-frame::before {
            top: 0;
            left: 0;
            border-width: 3px 0 0 3px;
        }

        .scan-frame::after {
            top: 0;
            right: 0;
            border-width: 3px 3px 0 0;
        }

        .sf-inner::before {
            bottom: 0;
            left: 0;
            border-width: 0 0 3px 3px;
        }

        .sf-inner::after {
            bottom: 0;
            right: 0;
            border-width: 0 3px 3px 0;
        }

        /* =========================================
                               BEAM ANIMATION
                            ========================================= */
        .scan-beam {
            position: absolute;
            left: 0;
            width: 100%;
            height: 3px;
            background: linear-gradient(90deg, transparent, #00d4ff, transparent);
            box-shadow: 0 0 15px rgba(0, 212, 255, 0.8);
            animation: beam 2.5s ease-in-out infinite;
        }

        @keyframes beam {
            0% {
                top: 0;
                opacity: 0;
            }

            10% {
                opacity: 1;
            }

            90% {
                opacity: 1;
            }

            100% {
                top: 100%;
                opacity: 0;
            }
        }

        .scan-placeholder {
            position: absolute;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 12px;
            color: rgba(255, 255, 255, 0.28);
        }

        .scan-placeholder i {
            font-size: 50px;
        }

        .scan-placeholder p {
            font-size: 14px;
            text-align: center;
            max-width: 220px;
        }

        /* =========================================
                               CONTROLS - Thanh điều khiển gọn
                            ========================================= */
        .scanner-controls {
            padding: 15px 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 15px;
            border-top: 1px solid rgba(255, 255, 255, 0.06);
            background: rgba(0, 0, 0, 0.2);
        }

        .ctrl-btn {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.08);
            color: var(--text-main);
            padding: 10px 18px;
            border-radius: 12px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
            font-weight: 600;
            transition: all .2s ease;
        }

        .ctrl-btn:hover {
            background: rgba(0, 212, 255, 0.08);
            border-color: rgba(0, 212, 255, 0.35);
            transform: translateY(-1px);
        }

        .shutter {
            width: 64px;
            height: 64px;
            border-radius: 50%;
            background: radial-gradient(circle, #0ea5e9, #0284c7);
            border: 4px solid rgba(255, 255, 255, 0.12);
            cursor: pointer;
            box-shadow: 0 0 20px rgba(0, 212, 255, 0.4);
            transition: all .2s ease;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .shutter:hover {
            transform: scale(1.05);
        }

        .shutter i {
            color: #fff;
            font-size: 20px;
        }

        .shutter:disabled {
            opacity: .4;
            cursor: not-allowed;
        }

        /* =========================================
                               SIDEBAR & CARDS
                            ========================================= */
        .sidebar-card {
            background: linear-gradient(180deg, rgba(16, 24, 42, 0.94), rgba(10, 16, 30, 0.9));
            border: 1px solid rgba(255, 255, 255, 0.06);
            border-radius: 20px;
            padding: 18px;
            margin-bottom: 20px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
        }

        .sidebar-card h4 {
            font-size: 16px;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 8px;
            color: var(--primary);
            margin-bottom: 15px;
        }

        .tip-item {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            margin-bottom: 12px;
        }

        .tip-icon {
            width: 30px;
            height: 30px;
            border-radius: 8px;
            background: rgba(255, 255, 255, 0.05);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary);
            font-size: 12px;
            flex-shrink: 0;
        }

        .tip-item p {
            font-size: 13px;
            color: var(--text-muted);
            line-height: 1.5;
        }

        .rate-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 8px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }

        .rate-row .cur {
            font-weight: 600;
            font-size: 13px;
            display: flex;
            gap: 6px;
        }

        .rate-row .val {
            color: var(--primary);
            font-weight: 700;
            font-size: 15px;
        }

        /* =========================================
                               MOBILE RESPONSIVE
                            ========================================= */
        @media (max-width: 992px) {
            .scan-layout {
                grid-template-columns: 1fr;
            }

            .scan-sidebar {
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 20px;
            }
        }

        @media (max-width: 768px) {
            .scan-sidebar {
                grid-template-columns: 1fr;
            }

            .scanner-viewport {
                min-height: 350px;
            }

            .scan-frame {
                width: 80%;
                height: 60%;
            }
        }
    </style>
@endpush

@section('content')
    <div class="scan-page">
        <div class="scan-page-header">
            <div class="section-label">AI Scanner</div>
            <h1 class="text-gradient">Quét Tiền Tệ</h1>
            <p>Đặt tờ tiền vào khung quét — AI sẽ nhận diện mệnh giá và tỷ giá tức thì.</p>
        </div>

        <div class="scan-layout">
            <!-- LEFT: SCANNER -->
            <div class="scanner-box">
                <div class="scanner-viewport">
                    <video id="camera-feed" autoplay muted playsinline></video>
                    <img id="preview-img" src="" alt="Preview">

                    <div class="scan-overlay">
                        <div class="scan-frame">
                            <div class="scan-beam"></div>
                            <div class="sf-inner"></div>
                        </div>
                    </div>

                    <div class="scan-placeholder" id="scan-placeholder">
                        <i class="fa-regular fa-image"></i>
                        <p>Nhấn <strong>Bật Camera</strong> hoặc <strong>Tải ảnh</strong> để bắt đầu</p>
                    </div>

                    <div class="loading-overlay" id="loading-overlay">
                        <div class="spinner"></div>
                        <p>Đang phân tích AI...</p>
                    </div>
                </div>

                <!-- CONTROLS -->
                <div class="scanner-controls">
                    <button class="ctrl-btn" id="btn-toggle-camera">
                        <i class="fa-solid fa-video"></i> Bật Camera
                    </button>
                    <button class="shutter" id="shutter-btn" disabled>
                        <i class="fa-solid fa-circle-dot"></i>
                    </button>
                    <button class="ctrl-btn" id="btn-upload">
                        <i class="fa-solid fa-image"></i> Tải ảnh
                    </button>
                </div>

                <input type="file" id="file-input" accept="image/*" style="display:none">
            </div>

            <!-- RIGHT: SIDEBAR -->
            <div class="scan-sidebar">
                <div class="sidebar-card">
                    <h4><i class="fa-regular fa-lightbulb"></i> Mẹo quét nhanh</h4>
                    <div class="tip-item">
                        <div class="tip-icon"><i class="fa-solid fa-sun"></i></div>
                        <p>Đảm bảo đủ ánh sáng, tránh bị lóa.</p>
                    </div>
                    <div class="tip-item">
                        <div class="tip-icon"><i class="fa-solid fa-expand"></i></div>
                        <p>Giữ tiền nằm gọn trong khung hình.</p>
                    </div>
                    <div class="tip-item">
                        <div class="tip-icon"><i class="fa-solid fa-rotate"></i></div>
                        <p>Có thể quét cả hai mặt tờ tiền.</p>
                    </div>
                </div>

                <div class="sidebar-card">
                    <h4><i class="fa-solid fa-bolt"></i> Tỷ giá hôm nay</h4>
                    <div class="rate-row">
                        <div class="cur"><span>🇺🇸</span> USD/VND</div>
                        <div class="val">25,410</div>
                    </div>
                    <div class="rate-row">
                        <div class="cur"><span>🇪🇺</span> EUR/VND</div>
                        <div class="val">27,650</div>
                    </div>
                    <div class="rate-row">
                        <div class="cur"><span>🇯🇵</span> JPY/VND</div>
                        <div class="val">165.4</div>
                    </div>
                    <a href="{{ route('rates') }}" class="btn btn-outline w-full mt-4"
                        style="justify-content:center; font-size: 13px;">
                        <i class="fa-solid fa-chart-line"></i> Xem tất cả
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection