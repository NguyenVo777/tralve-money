@extends('layouts.app')
@section('title', 'Quét Tiền Tệ AI')

@push('styles')
<style>
.scan-page{width:100%;max-width:1100px;margin:0 auto;padding:25px clamp(15px,2vw,30px) 60px;min-height:calc(100vh - 80px);display:flex;flex-direction:column;justify-content:center}
.scan-page-header{text-align:center;margin-bottom:25px;max-width:700px;margin-inline:auto}
.scan-page-header h1{font-size:clamp(32px,3.5vw,48px);font-weight:800;margin-bottom:8px;letter-spacing:-1px;line-height:1.1}
.scan-page-header p{color:var(--text-muted);font-size:14px;max-width:550px;margin:0 auto;line-height:1.6}
.scan-layout{display:grid;grid-template-columns:minmax(0,1fr) 340px;gap:20px;align-items:start}
.scanner-box{background:linear-gradient(180deg,rgba(13,21,38,.96),rgba(7,12,24,.94));border:1px solid rgba(255,255,255,.08);border-radius:20px;overflow:hidden;position:relative;box-shadow:0 10px 40px rgba(0,0,0,.35)}
.scanner-viewport{width:100%;min-height:420px;background:#000;position:relative;display:flex;align-items:center;justify-content:center;overflow:hidden}
#camera-feed,#preview-img{width:100%;height:100%;object-fit:cover;position:absolute;inset:0}
#preview-img{display:none}
.scan-overlay{position:absolute;inset:0;display:flex;align-items:center;justify-content:center;pointer-events:none}
.scan-frame{width:65%;height:50%;position:relative;border-radius:16px;background:linear-gradient(rgba(0,212,255,.03),rgba(0,212,255,.01));backdrop-filter:blur(2px)}
.scan-frame::before,.scan-frame::after,.sf-inner::before,.sf-inner::after{content:'';position:absolute;width:30px;height:30px;border-color:var(--primary);border-style:solid;border-radius:4px}
.scan-frame::before{top:0;left:0;border-width:3px 0 0 3px}
.scan-frame::after{top:0;right:0;border-width:3px 3px 0 0}
.sf-inner::before{bottom:0;left:0;border-width:0 0 3px 3px}
.sf-inner::after{bottom:0;right:0;border-width:0 3px 3px 0}
.scan-beam{position:absolute;left:0;width:100%;height:3px;background:linear-gradient(90deg,transparent,#00d4ff,transparent);box-shadow:0 0 15px rgba(0,212,255,.8);animation:beam 2.5s ease-in-out infinite}
@keyframes beam{0%{top:0;opacity:0}10%{opacity:1}90%{opacity:1}100%{top:100%;opacity:0}}
.scan-placeholder{position:absolute;display:flex;flex-direction:column;align-items:center;gap:12px;color:rgba(255,255,255,.28)}
.scan-placeholder i{font-size:50px}
.scan-placeholder p{font-size:14px;text-align:center;max-width:220px}
.scanner-controls{padding:15px 20px;display:flex;align-items:center;justify-content:center;gap:15px;border-top:1px solid rgba(255,255,255,.06);background:rgba(0,0,0,.2)}
.ctrl-btn{background:rgba(255,255,255,.05);border:1px solid rgba(255,255,255,.08);color:var(--text-main);padding:10px 18px;border-radius:12px;cursor:pointer;display:flex;align-items:center;gap:8px;font-size:13px;font-weight:600;transition:all .2s ease}
.ctrl-btn:hover{background:rgba(0,212,255,.08);border-color:rgba(0,212,255,.35);transform:translateY(-1px)}
.shutter{width:64px;height:64px;border-radius:50%;background:radial-gradient(circle,#0ea5e9,#0284c7);border:4px solid rgba(255,255,255,.12);cursor:pointer;box-shadow:0 0 20px rgba(0,212,255,.4);transition:all .2s ease;display:flex;align-items:center;justify-content:center}
.shutter:hover{transform:scale(1.05)}
.shutter i{color:#fff;font-size:20px}
.shutter:disabled{opacity:.4;cursor:not-allowed}

/* ── SIDEBAR ── */
.sidebar-card{background:linear-gradient(180deg,rgba(16,24,42,.94),rgba(10,16,30,.9));border:1px solid rgba(255,255,255,.06);border-radius:20px;padding:18px;margin-bottom:20px;box-shadow:0 8px 25px rgba(0,0,0,.2)}
.sidebar-card h4{font-size:16px;font-weight:700;display:flex;align-items:center;gap:8px;color:var(--primary);margin-bottom:15px}
.tip-item{display:flex;align-items:flex-start;gap:10px;margin-bottom:12px}
.tip-icon{width:30px;height:30px;border-radius:8px;background:rgba(255,255,255,.05);display:flex;align-items:center;justify-content:center;color:var(--primary);font-size:12px;flex-shrink:0}
.tip-item p{font-size:13px;color:var(--text-muted);line-height:1.5}

/* ── LOADING ── */
.loading-overlay{position:absolute;inset:0;background:rgba(0,0,0,.7);display:none;flex-direction:column;align-items:center;justify-content:center;gap:16px;z-index:10;backdrop-filter:blur(4px)}
.loading-overlay.active{display:flex}
.loading-overlay p{color:var(--primary);font-size:14px;font-weight:600}

/* ── RESULT PANEL ── */
.result-panel{display:none;animation:fadeSlideUp .5s ease}
.result-panel.active{display:block}
@keyframes fadeSlideUp{from{opacity:0;transform:translateY(20px)}to{opacity:1;transform:translateY(0)}}

.result-main-card{border-radius:16px;padding:24px;margin-bottom:16px;border:1.5px solid;position:relative;overflow:hidden}
.result-main-card::before{content:'';position:absolute;inset:0;opacity:.06;background:radial-gradient(circle at 20% 50%,currentColor,transparent 70%)}
.result-main-card .rmc-badge{display:inline-block;font-size:.72rem;font-weight:700;letter-spacing:1.5px;padding:4px 14px;border-radius:20px;margin-bottom:12px}
.result-main-card .rmc-label{font-size:.78rem;color:rgba(255,255,255,.5);margin-bottom:4px;text-transform:uppercase;letter-spacing:1px}
.result-main-card .rmc-value{font-size:2rem;font-weight:800;letter-spacing:1px;line-height:1.2}
.result-main-card .rmc-conf{font-size:.9rem;color:rgba(255,255,255,.55);margin-top:6px}
.conf-bar-bg{background:rgba(255,255,255,.08);border-radius:8px;height:10px;margin:10px 0 0;overflow:hidden}
.conf-bar-fill{height:100%;border-radius:8px;transition:width .8s ease}

/* Currency-specific colors */
.rc-vnd{background:linear-gradient(135deg,#1a2e1e,#1e3824);border-color:#48bb78;color:#9ae6b4}
.rc-vnd .rmc-badge{background:#276749;color:#9ae6b4}
.rc-vnd .conf-bar-fill{background:linear-gradient(90deg,#276749,#48bb78)}
.rc-usd{background:linear-gradient(135deg,#0d2137,#0a2a4a);border-color:#63b3ed;color:#bee3f8}
.rc-usd .rmc-badge{background:#1a4a7a;color:#bee3f8}
.rc-usd .conf-bar-fill{background:linear-gradient(90deg,#1a4a7a,#63b3ed)}
.rc-idr{background:linear-gradient(135deg,#1a1a0d,#2a280a);border-color:#d69e2e;color:#faf089}
.rc-idr .rmc-badge{background:#5a3e00;color:#faf089}
.rc-idr .conf-bar-fill{background:linear-gradient(90deg,#5a3e00,#d69e2e)}
.rc-myr{background:linear-gradient(135deg,#0d2020,#0a2a2a);border-color:#38b2ac;color:#9decf9}
.rc-myr .rmc-badge{background:#1a4a4a;color:#9decf9}
.rc-myr .conf-bar-fill{background:linear-gradient(90deg,#1a4a4a,#38b2ac)}
.rc-php{background:linear-gradient(135deg,#2d0d1a,#3b0f25);border-color:#ed64a6;color:#fbb6ce}
.rc-php .rmc-badge{background:#6b1a3a;color:#fbb6ce}
.rc-php .conf-bar-fill{background:linear-gradient(90deg,#6b1a3a,#ed64a6)}
.rc-sgd{background:linear-gradient(135deg,#1a0d2e,#250f3b);border-color:#9f7aea;color:#e9d8fd}
.rc-sgd .rmc-badge{background:#44267a;color:#e9d8fd}
.rc-sgd .conf-bar-fill{background:linear-gradient(90deg,#44267a,#9f7aea)}
.rc-thb{background:linear-gradient(135deg,#2d1f0d,#3b280a);border-color:#ed8936;color:#fbd38d}
.rc-thb .rmc-badge{background:#6b3a00;color:#fbd38d}
.rc-thb .conf-bar-fill{background:linear-gradient(90deg,#6b3a00,#ed8936)}
.rc-fake{background:linear-gradient(135deg,#2d1515,#3b1a1a);border-color:#fc8181;color:#feb2b2}
.rc-fake .rmc-badge{background:#742a2a;color:#feb2b2}
.rc-fake .conf-bar-fill{background:linear-gradient(90deg,#742a2a,#fc8181)}
.rc-none{background:linear-gradient(135deg,#1a1a1a,#2a2a2a);border-color:#718096;color:#a0aec0}
.rc-none .rmc-badge{background:#2d3748;color:#a0aec0}
.rc-none .conf-bar-fill{background:linear-gradient(90deg,#2d3748,#718096)}

.warn-box{border-radius:10px;padding:12px 16px;font-size:.88rem;margin-top:12px;line-height:1.7}
.warn-fake{background:#2d1010;border:1.5px solid #fc8181;color:#feb2b2}
.warn-none{background:#1a1a2e;border:1px solid #718096;color:#a0aec0}
.warn-low{background:#2d2010;border:1px solid #b7791f;color:#fbd38d}

/* ── RESPONSIVE ── */
@media(max-width:992px){.scan-layout{grid-template-columns:1fr}}
@media(max-width:768px){.scanner-viewport{min-height:350px}.scan-frame{width:80%;height:60%}}
</style>
@endpush

@section('content')
<div class="scan-page">
    <div class="scan-page-header">
        <div class="section-label">AI Scanner</div>
        <h1 class="text-gradient">Quét Tiền Tệ</h1>
        <p>Đặt tờ tiền vào khung quét — AI sẽ nhận diện mệnh giá, loại tiền và xác thực thật/giả.</p>
    </div>

    <div class="scan-layout">
        <!-- LEFT: SCANNER -->
        <div class="scanner-box">
            <div class="scanner-viewport">
                <video id="camera-feed" autoplay muted playsinline></video>
                <img id="preview-img" src="" alt="Preview">
                <canvas id="capture-canvas" style="display:none"></canvas>

                <div class="scan-overlay" id="scan-overlay">
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
                <button class="shutter" id="shutter-btn" disabled title="Chụp & Nhận diện">
                    <i class="fa-solid fa-circle-dot"></i>
                </button>
                <button class="ctrl-btn" id="btn-upload">
                    <i class="fa-solid fa-image"></i> Tải ảnh
                </button>
            </div>
            <input type="file" id="file-input" accept="image/jpeg,image/png,image/webp" style="display:none">
        </div>

        <!-- RIGHT: SIDEBAR -->
        <div class="scan-sidebar">
            <!-- RESULT PANEL (hidden by default) -->
            <div class="result-panel" id="result-panel">
                <div class="result-main-card" id="result-card">
                    <div class="rmc-badge" id="res-badge"></div>
                    <div class="rmc-label" id="res-title"></div>
                    <div class="rmc-value" id="res-value"></div>
                    <div class="rmc-conf" id="res-conf"></div>
                    <div class="conf-bar-bg"><div class="conf-bar-fill" id="res-bar"></div></div>
                </div>
                <div id="res-warn"></div>
                <button class="ctrl-btn w-full mt-4" id="btn-retry" style="justify-content:center;width:100%">
                    <i class="fa-solid fa-rotate-right"></i> Quét lại
                </button>
            </div>

            <!-- TIPS -->
            <div class="sidebar-card" id="tips-card">
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
                <div class="tip-item">
                    <div class="tip-icon"><i class="fa-solid fa-globe"></i></div>
                    <p>Hỗ trợ VND · USD · IDR · MYR · PHP · SGD · THB</p>
                </div>
            </div>

            <div class="sidebar-card">
                <h4><i class="fa-solid fa-shield-halved"></i> Phát hiện tiền giả</h4>
                <div class="tip-item">
                    <div class="tip-icon"><i class="fa-solid fa-triangle-exclamation" style="color:#fc8181"></i></div>
                    <p>AI sẽ cảnh báo nếu phát hiện tiền giả hoặc tiền vàng mã.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
(function(){
    const API_URL = 'http://localhost:8001/predict';

    const video      = document.getElementById('camera-feed');
    const previewImg = document.getElementById('preview-img');
    const canvas     = document.getElementById('capture-canvas');
    const placeholder= document.getElementById('scan-placeholder');
    const overlay    = document.getElementById('scan-overlay');
    const loading    = document.getElementById('loading-overlay');
    const fileInput  = document.getElementById('file-input');
    const shutterBtn = document.getElementById('shutter-btn');
    const camBtn     = document.getElementById('btn-toggle-camera');
    const uploadBtn  = document.getElementById('btn-upload');
    const retryBtn   = document.getElementById('btn-retry');
    const resultPanel= document.getElementById('result-panel');

    let cameraStream = null;
    let cameraOn = false;

    // ── Currency config ──
    const CUR = {
        VND:  {cls:'rc-vnd',  badge:'✅ TIỀN VIỆT NAM (VND)', flag:'🇻🇳'},
        USD:  {cls:'rc-usd',  badge:'💵 ĐÔ LA MỸ (USD)',      flag:'🇺🇸'},
        IDR:  {cls:'rc-idr',  badge:'🇮🇩 RUPIAH (IDR)',        flag:'🇮🇩'},
        MYR:  {cls:'rc-myr',  badge:'🇲🇾 RINGGIT (MYR)',       flag:'🇲🇾'},
        PHP:  {cls:'rc-php',  badge:'🇵🇭 PESO (PHP)',          flag:'🇵🇭'},
        SGD:  {cls:'rc-sgd',  badge:'🇸🇬 ĐÔ LA SGD',          flag:'🇸🇬'},
        THB:  {cls:'rc-thb',  badge:'🇹🇭 BAHT (THB)',          flag:'🇹🇭'},
        NONE: {cls:'rc-none', badge:'🚫 KHÔNG CÓ TIỀN',       flag:'❓'},
        FAKE: {cls:'rc-fake', badge:'🔴 TIỀN GIẢ / VÀNG MÃ',  flag:'⚠️'},
    };

    // ── Display name helper ──
    function displayName(raw, currency, authenticity){
        const prefixes = ['fake_VN_','VN_','USD_','IDR_','MYR_','PHP_','SGD_','THB_'];
        let clean = raw;
        prefixes.forEach(p => clean = clean.replace(p,''));
        const fmt = s => { try{ return parseInt(s).toLocaleString('vi-VN'); }catch(e){ return s; }};

        if(authenticity === 'fake') return fmt(clean) + ' ₫ ⚠️ (Giả)';
        if(currency === 'VND')  return fmt(clean) + ' ₫';
        if(currency === 'USD')  return '$' + clean;
        if(currency === 'IDR')  return 'Rp ' + fmt(clean);
        if(currency === 'MYR')  return 'RM ' + clean;
        if(currency === 'PHP')  return clean + ' Peso';
        if(currency === 'SGD')  return 'S$' + clean;
        if(currency === 'THB')  return fmt(clean) + ' ฿';
        if(currency === 'NONE') return 'Không có tiền';
        return clean;
    }

    // ── Camera toggle ──
    camBtn.addEventListener('click', async () => {
        if(cameraOn){
            stopCamera();
        } else {
            await startCamera();
        }
    });

    async function startCamera(){
        try {
            cameraStream = await navigator.mediaDevices.getUserMedia({
                video: { facingMode:'environment', width:{ideal:1280}, height:{ideal:720} }
            });
            video.srcObject = cameraStream;
            video.style.display = 'block';
            previewImg.style.display = 'none';
            placeholder.style.display = 'none';
            overlay.style.display = 'flex';
            shutterBtn.disabled = false;
            cameraOn = true;
            camBtn.innerHTML = '<i class="fa-solid fa-video-slash"></i> Tắt Camera';
            hideResult();
        } catch(e){
            showToast('Không thể truy cập camera: ' + e.message, 'error');
        }
    }

    function stopCamera(){
        if(cameraStream){
            cameraStream.getTracks().forEach(t => t.stop());
            cameraStream = null;
        }
        video.srcObject = null;
        video.style.display = 'none';
        overlay.style.display = 'none';
        placeholder.style.display = 'flex';
        shutterBtn.disabled = true;
        cameraOn = false;
        camBtn.innerHTML = '<i class="fa-solid fa-video"></i> Bật Camera';
    }

    // ── Shutter: capture frame ──
    shutterBtn.addEventListener('click', () => {
        if(!cameraOn) return;
        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;
        canvas.getContext('2d').drawImage(video, 0, 0);
        canvas.toBlob(blob => {
            if(blob) sendToAPI(blob, 'capture.jpg');
        }, 'image/jpeg', 0.92);
    });

    // ── Upload ──
    uploadBtn.addEventListener('click', () => fileInput.click());
    fileInput.addEventListener('change', e => {
        const file = e.target.files[0];
        if(!file) return;
        // Show preview
        const url = URL.createObjectURL(file);
        previewImg.src = url;
        previewImg.style.display = 'block';
        video.style.display = 'none';
        placeholder.style.display = 'none';
        overlay.style.display = 'flex';
        if(cameraOn) stopCamera();

        sendToAPI(file, file.name);
        fileInput.value = '';
    });

    // ── Send to FastAPI ──
    async function sendToAPI(blob, filename){
        loading.classList.add('active');
        hideResult();

        const formData = new FormData();
        formData.append('file', blob, filename);

        try {
            const resp = await fetch(API_URL, { method:'POST', body: formData });

            if(!resp.ok){
                const err = await resp.json().catch(() => ({detail:'Lỗi server'}));
                throw new Error(err.detail || `HTTP ${resp.status}`);
            }

            const data = await resp.json();
            showResult(data);
        } catch(e){
            showToast('❌ ' + e.message, 'error', 5000);
        } finally {
            loading.classList.remove('active');
        }
    }

    // ── Show result ──
    function showResult(data){
        const {class: rawClass, confidence, authenticity, currency_type} = data;
        const isFake = authenticity === 'fake';
        const styleKey = isFake ? 'FAKE' : (currency_type === 'NONE' ? 'NONE' : currency_type);
        const cur = CUR[styleKey] || CUR.NONE;

        const card  = document.getElementById('result-card');
        const badge = document.getElementById('res-badge');
        const title = document.getElementById('res-title');
        const value = document.getElementById('res-value');
        const conf  = document.getElementById('res-conf');
        const bar   = document.getElementById('res-bar');
        const warn  = document.getElementById('res-warn');

        // Reset classes
        card.className = 'result-main-card ' + cur.cls;
        badge.textContent = cur.badge;
        title.textContent = isFake ? 'PHÁT HIỆN TIỀN GIẢ' : 'MỆNH GIÁ';
        value.textContent = displayName(rawClass, currency_type, authenticity);
        conf.textContent = 'Độ tin cậy: ' + (confidence * 100).toFixed(1) + '%';
        bar.style.width = (confidence * 100).toFixed(1) + '%';

        // Warnings
        let warnHTML = '';
        if(isFake){
            warnHTML = '<div class="warn-box warn-fake">🚨 <strong>Cảnh báo:</strong> Tờ tiền này có dấu hiệu là <strong>tiền vàng mã hoặc tiền giả</strong>. Vui lòng kiểm tra kỹ các đặc điểm bảo an trước khi giao dịch.</div>';
        } else if(currency_type === 'NONE'){
            warnHTML = '<div class="warn-box warn-none">ℹ️ Không phát hiện tờ tiền trong ảnh. Hãy chụp lại ảnh có tờ tiền rõ ràng hơn.</div>';
        } else if(confidence < 0.60){
            warnHTML = '<div class="warn-box warn-low">⚠️ Độ tin cậy thấp — thử chụp lại ảnh rõ hơn, đủ ánh sáng và không bị che khuất.</div>';
        }
        warn.innerHTML = warnHTML;

        resultPanel.classList.add('active');
    }

    function hideResult(){
        resultPanel.classList.remove('active');
    }

    // ── Retry ──
    retryBtn.addEventListener('click', () => {
        hideResult();
        previewImg.style.display = 'none';
        if(!cameraOn){
            placeholder.style.display = 'flex';
            overlay.style.display = 'none';
        }
    });

})();
</script>
@endpush