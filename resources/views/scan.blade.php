@extends('layouts.app')
@section('title', 'Quét Tiền Tệ AI')

@push('styles')
<style>
.scan-page { padding: 40px 5% 60px; max-width: 1200px; margin: 0 auto; }
.scan-page-header { text-align: center; margin-bottom: 48px; }
.scan-page-header h1 { font-size: 40px; font-weight: 800; margin-bottom: 12px; }
.scan-page-header p { color: var(--text-muted); font-size: 16px; max-width: 520px; margin: 0 auto; }

.scan-layout { display: grid; grid-template-columns: 1fr 360px; gap: 28px; align-items: start; }

/* Scanner Box */
.scanner-box {
    background: rgba(13,21,38,0.8);
    border: 1px solid var(--border-light);
    border-radius: 24px;
    overflow: hidden;
    position: relative;
}
.scanner-viewport {
    width: 100%; aspect-ratio: 4/3;
    background: #000;
    position: relative;
    display: flex; align-items: center; justify-content: center;
    overflow: hidden;
}
#camera-feed, #preview-img {
    width: 100%; height: 100%;
    object-fit: cover;
    position: absolute; inset: 0;
}
#preview-img { display: none; }
.scan-overlay {
    position: absolute; inset: 0;
    display: flex; align-items: center; justify-content: center;
    pointer-events: none;
}
.scan-frame {
    width: 70%; height: 55%;
    position: relative;
}
.scan-frame::before,.scan-frame::after,
.sf-inner::before,.sf-inner::after {
    content: ''; position: absolute;
    width: 36px; height: 36px;
    border-color: var(--primary); border-style: solid;
    border-radius: 4px;
}
.scan-frame::before { top:0; left:0; border-width:3px 0 0 3px; }
.scan-frame::after  { top:0; right:0; border-width:3px 3px 0 0; }
.sf-inner::before   { bottom:0; left:0; border-width:0 0 3px 3px; }
.sf-inner::after    { bottom:0; right:0; border-width:0 3px 3px 0; }
.scan-beam {
    position: absolute; left: 0; width: 100%; height: 3px;
    background: linear-gradient(90deg, transparent, var(--primary), transparent);
    box-shadow: 0 0 12px var(--primary-glow);
    animation: beam 2.5s ease-in-out infinite;
}
@keyframes beam {
    0%   { top: 0; opacity: 0; }
    10%  { opacity: 1; }
    90%  { opacity: 1; }
    100% { top: 100%; opacity: 0; }
}
.scan-placeholder {
    position: absolute;
    display: flex; flex-direction: column; align-items: center; gap: 16px;
    color: rgba(255,255,255,0.25);
}
.scan-placeholder i { font-size: 64px; }
.scan-placeholder p { font-size: 14px; text-align: center; max-width: 200px; line-height: 1.5; }

/* Controls */
.scanner-controls {
    padding: 24px;
    display: flex; align-items: center; justify-content: center;
    gap: 24px;
    border-top: 1px solid var(--border-light);
    background: rgba(0,0,0,0.2);
}
.ctrl-btn {
    background: rgba(255,255,255,0.06);
    border: 1px solid var(--border-light);
    color: var(--text-main);
    padding: 12px 20px;
    border-radius: 12px;
    cursor: pointer;
    display: flex; align-items: center; gap: 8px;
    font-size: 14px; font-weight: 500;
    transition: 0.25s;
}
.ctrl-btn:hover { background: rgba(255,255,255,0.12); }
.shutter {
    width: 72px; height: 72px; border-radius: 50%;
    background: transparent;
    border: 3px solid rgba(0,212,255,0.5);
    cursor: pointer; position: relative;
    box-shadow: 0 0 20px var(--primary-glow);
    transition: 0.2s;
    display: flex; align-items: center; justify-content: center;
}
.shutter::after {
    content: '';
    width: 54px; height: 54px;
    background: linear-gradient(135deg, var(--secondary), var(--primary));
    border-radius: 50%; transition: 0.2s;
}
.shutter:hover::after { transform: scale(0.9); }
.shutter i { position: absolute; color: #fff; font-size: 22px; z-index: 2; }
.shutter:disabled { opacity: 0.4; cursor: not-allowed; }

/* Sidebar */
.scan-sidebar { display: flex; flex-direction: column; gap: 20px; }
.sidebar-card {
    background: rgba(13,21,38,0.8);
    border: 1px solid var(--border-light);
    border-radius: 20px;
    padding: 24px;
}
.sidebar-card h4 {
    font-size: 15px; font-weight: 700;
    display: flex; align-items: center; gap: 8px;
    color: var(--primary); margin-bottom: 20px;
}
.tip-item {
    display: flex; align-items: flex-start; gap: 12px;
    margin-bottom: 16px;
}
.tip-icon {
    width: 32px; height: 32px; border-radius: 8px;
    background: rgba(255,255,255,0.05);
    display: flex; align-items: center; justify-content: center;
    color: var(--primary); font-size: 13px; flex-shrink: 0;
}
.tip-item p { font-size: 13px; color: var(--text-muted); line-height: 1.5; }
.rate-row {
    display: flex; justify-content: space-between; align-items: center;
    padding: 12px 0;
    border-bottom: 1px solid rgba(255,255,255,0.04);
}
.rate-row:last-child { border: none; padding-bottom: 0; }
.rate-row .cur { font-weight: 600; display: flex; align-items: center; gap: 8px; }
.rate-row .val { color: var(--primary); font-weight: 700; }
.rate-row .chg.up { color: var(--accent); font-size: 12px; }
.rate-row .chg.dn { color: var(--danger); font-size: 12px; }

/* Loading overlay */
.loading-overlay {
    position: absolute; inset: 0;
    background: rgba(6,12,26,0.85);
    display: none; flex-direction: column;
    align-items: center; justify-content: center; gap: 16px;
    z-index: 10;
}
.loading-overlay.active { display: flex; }
.loading-overlay p { color: var(--primary); font-size: 14px; font-weight: 600; }

/* Upload zone */
.upload-zone {
    border: 2px dashed var(--border);
    border-radius: 16px;
    padding: 32px;
    text-align: center;
    cursor: pointer;
    transition: 0.3s;
    display: none;
}
.upload-zone.visible { display: block; }
.upload-zone:hover { border-color: var(--primary); background: rgba(0,212,255,0.03); }
.upload-zone i { font-size: 40px; color: var(--text-muted); margin-bottom: 12px; }
.upload-zone p { color: var(--text-muted); font-size: 14px; }
.upload-zone span { color: var(--primary); font-weight: 600; }

@media (max-width: 900px) {
    .scan-layout { grid-template-columns: 1fr; }
}
</style>
@endpush

@section('content')
<div class="scan-page">
    <div class="scan-page-header">
        <div class="section-label">AI Scanner</div>
        <h1 class="text-gradient">Quét Tiền Tệ</h1>
        <p>Đặt tờ tiền vào khung quét — AI sẽ nhận diện tức thì mệnh giá và tỷ giá hiện tại.</p>
    </div>

    <div class="scan-layout">
        <!-- Main Scanner -->
        <div class="scanner-box">
            <div class="scanner-viewport" id="scanner-viewport">
                <video id="camera-feed" autoplay muted playsinline></video>
                <img id="preview-img" src="" alt="Preview">
                <div class="scan-overlay">
                    <div class="scan-frame">
                        <div class="scan-beam" id="scan-beam"></div>
                        <div class="sf-inner"></div>
                    </div>
                </div>
                <div class="scan-placeholder" id="scan-placeholder">
                    <i class="fa-regular fa-image"></i>
                    <p>Nhấn <strong>Bật Camera</strong> hoặc <strong>Tải ảnh</strong> để bắt đầu</p>
                </div>
                <div class="loading-overlay" id="loading-overlay">
                    <div class="spinner"></div>
                    <p>Đang phân tích bằng AI...</p>
                </div>
            </div>

            <!-- Upload Zone -->
            <div class="upload-zone" id="upload-zone" onclick="document.getElementById('file-input').click()">
                <i class="fa-solid fa-cloud-arrow-up"></i>
                <p>Kéo thả ảnh vào đây hoặc <span>chọn file</span></p>
                <p style="font-size:12px;margin-top:8px;">JPG, PNG, WEBP – tối đa 10MB</p>
            </div>
            <input type="file" id="file-input" accept="image/*" style="display:none">

            <div class="scanner-controls">
                <button class="ctrl-btn" id="btn-toggle-camera" onclick="toggleCamera()">
                    <i class="fa-solid fa-video"></i> Bật Camera
                </button>
                <button class="shutter" id="shutter-btn" onclick="captureAndAnalyze()" disabled title="Chụp & Phân tích">
                    <i class="fa-solid fa-circle-dot"></i>
                </button>
                <button class="ctrl-btn" id="btn-upload" onclick="toggleUploadMode()">
                    <i class="fa-solid fa-image"></i> Tải ảnh
                </button>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="scan-sidebar">
            <div class="sidebar-card">
                <h4><i class="fa-regular fa-lightbulb"></i> Mẹo quét nhanh</h4>
                <div class="tip-item">
                    <div class="tip-icon"><i class="fa-solid fa-sun"></i></div>
                    <p>Đảm bảo đủ ánh sáng, tránh bóng tối hoặc phản chiếu.</p>
                </div>
                <div class="tip-item">
                    <div class="tip-icon"><i class="fa-solid fa-expand"></i></div>
                    <p>Giữ camera ổn định, tờ tiền nằm gọn trong khung hình.</p>
                </div>
                <div class="tip-item">
                    <div class="tip-icon"><i class="fa-solid fa-layer-group"></i></div>
                    <p>Đặt tờ tiền trên nền trơn, tránh họa tiết phức tạp.</p>
                </div>
                <div class="tip-item">
                    <div class="tip-icon"><i class="fa-solid fa-rotate"></i></div>
                    <p>Có thể quét cả mặt trước lẫn mặt sau của tờ tiền.</p>
                </div>
            </div>

            <div class="sidebar-card">
                <h4><i class="fa-solid fa-bolt"></i> Tỷ giá hôm nay</h4>
                <div class="rate-row">
                    <div class="cur"><span>🇺🇸</span> USD/VND</div>
                    <div>
                        <div class="val">25,410</div>
                        <div class="chg up">▲ +0.12%</div>
                    </div>
                </div>
                <div class="rate-row">
                    <div class="cur"><span>🇪🇺</span> EUR/VND</div>
                    <div>
                        <div class="val">27,650</div>
                        <div class="chg dn">▼ -0.08%</div>
                    </div>
                </div>
                <div class="rate-row">
                    <div class="cur"><span>🇯🇵</span> JPY/VND</div>
                    <div>
                        <div class="val">165.4</div>
                        <div class="chg up">▲ +0.31%</div>
                    </div>
                </div>
                <div class="rate-row">
                    <div class="cur"><span>🇬🇧</span> GBP/VND</div>
                    <div>
                        <div class="val">31,200</div>
                        <div class="chg up">▲ +0.05%</div>
                    </div>
                </div>
                <a href="{{ route('rates') }}" class="btn btn-outline w-full mt-4" style="justify-content:center;">
                    <i class="fa-solid fa-chart-line"></i> Xem tất cả tỷ giá
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
let stream = null;
let cameraActive = false;
let uploadMode = false;

async function toggleCamera() {
    const btn = document.getElementById('btn-toggle-camera');
    const placeholder = document.getElementById('scan-placeholder');
    const video = document.getElementById('camera-feed');
    const shutter = document.getElementById('shutter-btn');
    const uploadZone = document.getElementById('upload-zone');

    if (cameraActive) {
        // Turn off
        if (stream) { stream.getTracks().forEach(t => t.stop()); stream = null; }
        video.style.display = 'none';
        placeholder.style.display = 'flex';
        btn.innerHTML = '<i class="fa-solid fa-video"></i> Bật Camera';
        shutter.disabled = true;
        cameraActive = false;
    } else {
        try {
            uploadZone.classList.remove('visible');
            uploadMode = false;
            placeholder.style.display = 'none';
            video.style.display = 'block';
            document.getElementById('preview-img').style.display = 'none';
            stream = await navigator.mediaDevices.getUserMedia({ video: { facingMode: 'environment' } });
            video.srcObject = stream;
            btn.innerHTML = '<i class="fa-solid fa-video-slash"></i> Tắt Camera';
            shutter.disabled = false;
            cameraActive = true;
        } catch (e) {
            showToast('Không thể truy cập camera. Vui lòng kiểm tra quyền truy cập.', 'error');
            placeholder.style.display = 'flex';
        }
    }
}

function toggleUploadMode() {
    const uploadZone = document.getElementById('upload-zone');
    const placeholder = document.getElementById('scan-placeholder');
    const video = document.getElementById('camera-feed');

    if (cameraActive) {
        stream.getTracks().forEach(t => t.stop()); stream = null;
        video.style.display = 'none';
        document.getElementById('btn-toggle-camera').innerHTML = '<i class="fa-solid fa-video"></i> Bật Camera';
        document.getElementById('shutter-btn').disabled = true;
        cameraActive = false;
    }

    uploadMode = !uploadMode;
    if (uploadMode) {
        uploadZone.classList.add('visible');
        placeholder.style.display = 'none';
    } else {
        uploadZone.classList.remove('visible');
        placeholder.style.display = 'flex';
    }
}

document.getElementById('file-input').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = (ev) => {
        const img = document.getElementById('preview-img');
        img.src = ev.target.result;
        img.style.display = 'block';
        document.getElementById('upload-zone').classList.remove('visible');
        document.getElementById('shutter-btn').disabled = false;
        document.getElementById('scan-placeholder').style.display = 'none';
        showToast('Ảnh đã tải lên. Nhấn nút chụp để phân tích!', 'info');
    };
    reader.readAsDataURL(file);
});

// Drag & drop
const uploadZone = document.getElementById('upload-zone');
uploadZone.addEventListener('dragover', e => { e.preventDefault(); uploadZone.style.borderColor = 'var(--primary)'; });
uploadZone.addEventListener('dragleave', () => { uploadZone.style.borderColor = ''; });
uploadZone.addEventListener('drop', e => {
    e.preventDefault(); uploadZone.style.borderColor = '';
    const file = e.dataTransfer.files[0];
    if (file && file.type.startsWith('image/')) {
        const input = document.getElementById('file-input');
        const dt = new DataTransfer(); dt.items.add(file); input.files = dt.files;
        input.dispatchEvent(new Event('change'));
    }
});

async function captureAndAnalyze() {
    const overlay = document.getElementById('loading-overlay');
    overlay.classList.add('active');

    // Simulate AI processing
    await new Promise(r => setTimeout(r, 2200));
    overlay.classList.remove('active');
    window.location.href = '{{ route("result") }}';
}
</script>
@endpush
