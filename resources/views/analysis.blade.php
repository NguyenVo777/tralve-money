@extends('layouts.app')
@section('title', 'AI Phân tích Tiền Hư hỏng')

@push('styles')
<style>
.analysis-page { padding: 40px 5% 80px; max-width: 1000px; margin: 0 auto; }
.analysis-header { text-align: center; margin-bottom: 48px; }
.analysis-header h1 { font-size: 40px; font-weight: 800; margin-bottom: 12px; }

.upload-section {
    background: rgba(255, 255, 255, 0.03);
    border: 2px dashed var(--border);
    border-radius: 24px;
    padding: 60px 40px;
    text-align: center;
    transition: 0.3s;
    cursor: pointer;
    position: relative;
    overflow: hidden;
}
.upload-section:hover, .upload-section.dragover {
    border-color: var(--primary);
    background: rgba(0, 212, 255, 0.05);
}
.upload-icon {
    font-size: 64px;
    color: var(--primary);
    margin-bottom: 24px;
    display: inline-block;
}
.upload-section h3 { font-size: 24px; font-weight: 700; margin-bottom: 12px; }
.upload-section p { color: var(--text-muted); font-size: 16px; }

#image-preview {
    max-width: 100%;
    max-height: 400px;
    border-radius: 16px;
    display: none;
    margin: 20px auto;
    box-shadow: 0 10px 30px rgba(0,0,0,0.5);
}

.scanning-overlay {
    position: absolute;
    top: 0; left: 0; width: 100%; height: 2px;
    background: var(--primary);
    box-shadow: 0 0 15px var(--primary);
    display: none;
    z-index: 10;
}

@keyframes scan {
    0% { top: 0; }
    100% { top: 100%; }
}

.results-area {
    margin-top: 40px;
    display: none;
}
.result-card {
    background: var(--bg-surface);
    border: 1px solid var(--border);
    border-radius: 24px;
    padding: 32px;
    display: grid;
    grid-template-columns: 1fr 1.5fr;
    gap: 32px;
}
.result-score {
    text-align: center;
    padding: 32px;
    background: rgba(0,0,0,0.3);
    border-radius: 20px;
    display: flex;
    flex-direction: column;
    justify-content: center;
}
.score-val { font-size: 64px; font-weight: 800; color: var(--accent); line-height: 1; }
.score-label { font-size: 14px; color: var(--text-muted); margin-top: 8px; text-transform: uppercase; letter-spacing: 1px; }

.result-details h2 { font-size: 24px; font-weight: 700; margin-bottom: 16px; color: var(--primary); }
.detail-item { margin-bottom: 20px; }
.detail-item .label { font-size: 13px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; margin-bottom: 4px; }
.detail-item .val { font-size: 16px; font-weight: 600; }

.status-badge {
    display: inline-block;
    padding: 6px 16px;
    border-radius: 20px;
    font-size: 14px;
    font-weight: 700;
}
.status-good { background: rgba(0, 229, 195, 0.15); color: var(--accent); }
.status-warn { background: rgba(255, 193, 7, 0.15); color: #ffc107; }
.status-bad { background: rgba(255, 77, 109, 0.15); color: var(--danger); }

@media (max-width: 768px) {
    .result-card { grid-template-columns: 1fr; }
}
</style>
@endpush

@section('content')
<div class="analysis-page">
    <div class="analysis-header">
        <div class="section-label">AI Phân tích & Thẩm định</div>
        <h1 class="text-gradient">Giám định Tiền Hư hỏng</h1>
        <p>Sử dụng trí tuệ nhân tạo để phân tích mức độ hư hại của tiền và tư vấn khả năng thu đổi.</p>
    </div>

    <div class="upload-section" id="drop-area" onclick="document.getElementById('file-input').click()">
        <input type="file" id="file-input" hidden accept="image/*" onchange="handleFile(this.files[0])">
        <div class="scanning-overlay" id="scanner"></div>
        
        <div id="upload-prompt">
            <div class="upload-icon"><i class="fa-solid fa-cloud-arrow-up"></i></div>
            <h3>Tải lên ảnh tờ tiền</h3>
            <p>Kéo thả hoặc nhấn để chọn ảnh (Chụp rõ nét, đủ 2 mặt nếu có thể)</p>
        </div>
        
        <img id="image-preview" src="" alt="Preview">
    </div>

    <div class="results-area" id="results">
        <div class="result-card glass-card">
            <div class="result-score">
                <div class="score-val" id="score">85%</div>
                <div class="score-label">Độ nguyên vẹn</div>
                <div style="margin-top:20px">
                    <span class="status-badge status-warn" id="status-text">CẦN XEM XÉT</span>
                </div>
            </div>
            
            <div class="result-details">
                <h2>Kết quả phân tích AI</h2>
                
                <div class="detail-item">
                    <div class="label">Tình trạng phát hiện</div>
                    <div class="val" id="detection">Phát hiện vết rách ở góc trái, mờ hình mờ bảo an.</div>
                </div>
                
                <div class="detail-item">
                    <div class="label">Khả năng thu đổi</div>
                    <div class="val" id="exchange-rate">Có thể thu đổi (Phí 5-10% tùy ngân hàng).</div>
                </div>
                
                <div class="detail-item">
                    <div class="label">Khuyến nghị</div>
                    <div class="val">Vui lòng mang đến Ngân hàng Nhà nước hoặc các chi nhánh ngân hàng thương mại để được giám định chính xác nhất.</div>
                </div>

                <div style="margin-top: 32px; display: flex; gap: 16px;">
                    <button class="btn btn-primary" onclick="location.reload()">
                        <i class="fa-solid fa-rotate-left"></i> Phân tích ảnh khác
                    </button>
                    <a href="{{ route('map') }}" class="btn btn-outline">
                        <i class="fa-solid fa-map-location-dot"></i> Tìm điểm thu đổi
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
const dropArea = document.getElementById('drop-area');
const scanner = document.getElementById('scanner');
const results = document.getElementById('results');
const uploadPrompt = document.getElementById('upload-prompt');
const preview = document.getElementById('image-preview');

['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
    dropArea.addEventListener(eventName, preventDefaults, false);
});

function preventDefaults(e) {
    e.preventDefault();
    e.stopPropagation();
}

['dragenter', 'dragover'].forEach(eventName => {
    dropArea.addEventListener(eventName, () => dropArea.classList.add('dragover'), false);
});

['dragleave', 'drop'].forEach(eventName => {
    dropArea.addEventListener(eventName, () => dropArea.classList.remove('dragover'), false);
});

dropArea.addEventListener('drop', (e) => {
    const dt = e.dataTransfer;
    const file = dt.files[0];
    handleFile(file);
}, false);

function handleFile(file) {
    if (!file || !file.type.startsWith('image/')) {
        showToast('Vui lòng chọn một tệp hình ảnh!', 'danger');
        return;
    }

    const reader = new FileReader();
    reader.readAsDataURL(file);
    reader.onloadend = function() {
        preview.src = reader.result;
        startAnalysis(file);
    }
}

function startAnalysis(file) {
    uploadPrompt.style.display = 'none';
    preview.style.display = 'block';
    scanner.style.display = 'block';
    scanner.style.animation = 'scan 2s linear infinite';
    
    showToast('Đang kết nối với AI chuyên gia...', 'info');

    const formData = new FormData();
    formData.append('image', file);
    formData.append('_token', '{{ csrf_token() }}');

    fetch('{{ route("analysis.post") }}', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            finishAnalysis(data.analysis);
        } else {
            showToast(data.message, 'danger');
            resetUI();
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Lỗi kết nối máy chủ!', 'danger');
        resetUI();
    });
}

function resetUI() {
    scanner.style.display = 'none';
    uploadPrompt.style.display = 'block';
    preview.style.display = 'none';
}

function finishAnalysis(result) {
    scanner.style.display = 'none';
    results.style.display = 'block';
    results.scrollIntoView({ behavior: 'smooth' });
    
    const scoreEl = document.getElementById('score');
    const statusEl = document.getElementById('status-text');
    const detectionEl = document.getElementById('detection');
    const exchangeEl = document.getElementById('exchange-rate');
    const adviceEl = results.querySelector('.detail-item:nth-child(3) .val');
    
    scoreEl.innerText = result.score + '%';
    statusEl.innerText = result.status_label;
    
    if (result.score > 80) {
        statusEl.className = 'status-badge status-good';
    } else if (result.score > 40) {
        statusEl.className = 'status-badge status-warn';
    } else {
        statusEl.className = 'status-badge status-bad';
    }
    
    detectionEl.innerText = result.detection;
    exchangeEl.innerText = result.exchange_rate;
    if(adviceEl) adviceEl.innerText = result.advice;
    
    showToast('Phân tích hoàn tất!', 'success');
}
</script>
@endpush
