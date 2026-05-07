@extends('layouts.app')
@section('title', 'Tỷ giá & Chuyển đổi')

@push('styles')
<style>
.rates-page { padding: 40px 5% 80px; max-width: 1300px; margin: 0 auto; }
.rates-header { text-align: center; margin-bottom: 48px; }
.rates-header h1 { font-size: 40px; font-weight: 800; margin-bottom: 12px; }
.rates-header p { color: var(--text-muted); font-size: 16px; max-width: 520px; margin: 0 auto; }

.dashboard-grid {
    display: grid;
    grid-template-columns: 1fr 1.2fr;
    gap: 32px;
}

/* CONVERTER */
.converter-card { padding: 32px; }
.converter-title { font-size: 20px; font-weight: 700; margin-bottom: 24px; display: flex; align-items: center; gap: 8px; }
.converter-title i { color: var(--primary); }
.conv-box {
    background: rgba(0,0,0,0.3);
    border: 1px solid var(--border);
    border-radius: 16px;
    padding: 20px;
    display: flex; justify-content: space-between; align-items: center;
    transition: 0.3s;
}
.conv-box:focus-within { border-color: var(--primary); box-shadow: 0 0 0 2px var(--primary-glow); }
.conv-box .label { font-size: 12px; font-weight: 600; color: var(--text-muted); margin-bottom: 8px; }
.conv-box .amount { background: transparent; border: none; outline: none; color: #fff; font-size: 32px; font-weight: 600; width: 100%; max-width: 200px; }
.conv-box .amount.result { color: var(--primary); }

/* Custom select */
.cur-select {
    display: flex; align-items: center; gap: 8px;
    background: var(--bg-surface); border: 1px solid var(--border);
    padding: 8px 16px; border-radius: 12px; cursor: pointer;
    font-weight: 600; transition: 0.3s;
}
.cur-select:hover { border-color: var(--primary); }
.cur-select img { width: 24px; height: 24px; border-radius: 50%; object-fit: cover; }

.swap-btn {
    width: 48px; height: 48px; border-radius: 50%;
    background: var(--bg-surface); border: 1px solid var(--border);
    color: var(--primary); font-size: 18px;
    display: flex; align-items: center; justify-content: center;
    margin: -16px auto; position: relative; z-index: 2;
    cursor: pointer; transition: 0.3s; box-shadow: 0 8px 20px rgba(0,0,0,0.3);
}
.swap-btn:hover { background: var(--primary); color: #fff; transform: rotate(180deg); }

.rate-info {
    display: flex; justify-content: space-between; align-items: center;
    font-size: 13px; color: var(--text-muted); margin: 24px 0;
}
.rate-info .highlight { color: var(--accent); font-weight: 600; font-size: 15px; }

/* HISTORY */
.history-list { margin-top: 32px; border-top: 1px solid var(--border-light); padding-top: 24px; }
.history-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; }
.history-header h3 { font-size: 16px; font-weight: 600; }
.history-header a { font-size: 12px; color: var(--text-muted); }
.history-header a:hover { color: var(--danger); }
.hist-item {
    display: flex; justify-content: space-between; align-items: center;
    padding: 12px; border-radius: 12px; transition: 0.3s;
}
.hist-item:hover { background: rgba(255,255,255,0.03); }
.hist-item .left { display: flex; align-items: center; gap: 16px; }
.hist-icon {
    width: 40px; height: 40px; border-radius: 10px;
    background: rgba(0,212,255,0.1); color: var(--primary);
    display: flex; align-items: center; justify-content: center;
}
.hist-item .pairs { font-weight: 600; font-size: 15px; margin-bottom: 4px; }
.hist-item .time { font-size: 12px; color: var(--text-muted); }
.hist-item .right { text-align: right; }
.hist-item .amt-from { font-weight: 600; color: var(--text-muted); font-size: 13px; margin-bottom: 2px; }
.hist-item .amt-to { font-weight: 700; color: var(--accent); font-size: 15px; }

/* CHART AREA */
.chart-card { padding: 32px; }
.chart-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 24px; }
.ch-left h2 { font-size: 28px; font-weight: 700; margin-bottom: 8px; }
.ch-left .stat { display: flex; align-items: center; gap: 12px; }
.ch-left .stat .val { font-size: 20px; font-weight: 600; color: var(--text-main); }
.ch-left .stat .chg { color: var(--accent); font-size: 14px; font-weight: 600; background: rgba(0,229,195,0.1); padding: 4px 10px; border-radius: 20px; }
.chart-tabs { display: flex; gap: 4px; background: rgba(0,0,0,0.3); padding: 6px; border-radius: 12px; border: 1px solid var(--border); }
.ctab { padding: 6px 14px; font-size: 13px; font-weight: 600; border-radius: 8px; cursor: pointer; color: var(--text-muted); transition: 0.3s; }
.ctab.active { background: var(--bg-surface-light); color: var(--primary); }

.chart-wrapper { height: 260px; margin-bottom: 32px; position: relative; }
.live-ping { position: absolute; top: 0; right: 0; display: flex; align-items: center; gap: 6px; font-size: 12px; font-weight: 600; color: var(--accent); }

/* MARKET INFO */
.market-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
.info-box {
    background: rgba(0,0,0,0.2); border: 1px solid var(--border);
    border-radius: 16px; padding: 20px;
}
.info-box.alert { border-color: rgba(255,77,109,0.3); background: rgba(255,77,109,0.05); }
.ib-head { display: flex; align-items: center; gap: 8px; font-size: 13px; font-weight: 700; text-transform: uppercase; margin-bottom: 12px; }
.ib-head.up { color: var(--primary); }
.ib-head.warn { color: var(--danger); }
.info-box p { font-size: 14px; color: var(--text-muted); line-height: 1.6; }

/* POPULAR PAIRS */
.pairs-section { margin-top: 40px; }
.pairs-header { font-size: 20px; font-weight: 700; margin-bottom: 24px; }
.pairs-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 20px; }
.pair-card {
    background: var(--bg-surface); border: 1px solid var(--border-light);
    border-radius: 16px; padding: 20px; text-align: center;
    transition: 0.3s; cursor: pointer;
}
.pair-card:hover { border-color: var(--primary); transform: translateY(-4px); box-shadow: 0 10px 20px rgba(0,0,0,0.3); }
.pair-card .lbl { font-size: 13px; color: var(--text-muted); font-weight: 600; margin-bottom: 12px; }
.pair-card .val { font-size: 24px; font-weight: 700; margin-bottom: 8px; }
.pair-card .chg.up { color: var(--accent); font-size: 13px; }
.pair-card .chg.dn { color: var(--danger); font-size: 13px; }

@media (max-width: 1024px) {
    .dashboard-grid { grid-template-columns: 1fr; }
}
</style>
@endpush

@section('content')
<div class="rates-page">
    <div class="rates-header">
        <div class="section-label">Tỷ giá & Chuyển đổi</div>
        <h1 class="text-gradient">Thị trường Tài chính</h1>
        <p>Cập nhật tỷ giá ngoại tệ theo thời gian thực. Chuyển đổi chính xác, thông minh và nhanh chóng.</p>
    </div>

    <div class="dashboard-grid">
        <!-- Left: Converter -->
        <div class="glass-card converter-card">
            <h2 class="converter-title"><i class="fa-solid fa-money-bill-transfer"></i> Chuyển đổi tiền tệ</h2>

            <div class="conv-box">
                <div>
                    <div class="label">BẠN CÓ</div>
                    <input type="text" class="amount" id="amt-from" value="1,000">
                </div>
                <div class="cur-select" id="cur-from">
                    <img src="https://flagcdn.com/w40/us.png" alt="USD">
                    <span>USD</span>
                    <i class="fa-solid fa-chevron-down" style="font-size:10px;color:var(--text-muted)"></i>
                </div>
            </div>

            <div class="swap-btn" onclick="swapCurrencies()"><i class="fa-solid fa-arrow-down-up-across-line"></i></div>

            <div class="conv-box">
                <div>
                    <div class="label">BẠN NHẬN ĐƯỢC</div>
                    <input type="text" class="amount result" id="amt-to" value="25,410,000" readonly>
                </div>
                <div class="cur-select" id="cur-to">
                    <img src="https://flagcdn.com/w40/vn.png" alt="VND">
                    <span>VND</span>
                    <i class="fa-solid fa-chevron-down" style="font-size:10px;color:var(--text-muted)"></i>
                </div>
            </div>

            <div class="rate-info">
                <span>Cập nhật lúc: <span id="update-time">14:02, Hôm nay</span></span>
                <span class="highlight">1 USD = 25,410 VND</span>
            </div>

            <button class="btn btn-primary w-full btn-lg" onclick="showToast('Đã lưu giao dịch vào lịch sử!', 'success')">
                <i class="fa-solid fa-bookmark"></i> Lưu quy đổi này
            </button>

            <!-- History -->
            <div class="history-list">
                <div class="history-header">
                    <h3>Lịch sử gần đây</h3>
                    <a href="#" onclick="event.preventDefault(); showToast('Đã xóa lịch sử', 'info')">Xóa tất cả</a>
                </div>

                <div class="hist-item">
                    <div class="left">
                        <div class="hist-icon"><i class="fa-solid fa-euro-sign"></i></div>
                        <div>
                            <div class="pairs">EUR → VND</div>
                            <div class="time">10 phút trước</div>
                        </div>
                    </div>
                    <div class="right">
                        <div class="amt-from">500 EUR</div>
                        <div class="amt-to">13,825,000 ₫</div>
                    </div>
                </div>

                <div class="hist-item">
                    <div class="left">
                        <div class="hist-icon"><i class="fa-solid fa-yen-sign"></i></div>
                        <div>
                            <div class="pairs">JPY → VND</div>
                            <div class="time">2 giờ trước</div>
                        </div>
                    </div>
                    <div class="right">
                        <div class="amt-from">10,000 JPY</div>
                        <div class="amt-to">1,654,000 ₫</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right: Chart & Market -->
        <div class="glass-card chart-card">
            <div class="chart-header">
                <div class="ch-left">
                    <h2>USD / VND</h2>
                    <div class="stat">
                        <span class="val">25,410</span>
                        <span class="chg">▲ +0.12%</span>
                    </div>
                </div>
                <div class="chart-tabs">
                    <div class="ctab">1N</div>
                    <div class="ctab active">1T</div>
                    <div class="ctab">1Th</div>
                    <div class="ctab">1N</div>
                </div>
            </div>

            <div class="chart-wrapper">
                <div class="live-ping">
                    <span class="pulse" style="width:8px;height:8px;background:var(--accent);border-radius:50%;display:inline-block"></span>
                    LIVE
                </div>
                <!-- SVG Chart Mockup -->
                <svg viewBox="0 0 500 200" style="width:100%;height:100%;overflow:visible;">
                    <defs>
                        <linearGradient id="cGrad" x1="0" x2="0" y1="0" y2="1">
                            <stop offset="0%" stop-color="rgba(0, 212, 255, 0.4)" />
                            <stop offset="100%" stop-color="rgba(0, 212, 255, 0)" />
                        </linearGradient>
                    </defs>
                    <!-- Grid -->
                    <line x1="0" y1="50" x2="500" y2="50" stroke="var(--border-light)" stroke-dasharray="4" />
                    <line x1="0" y1="100" x2="500" y2="100" stroke="var(--border-light)" stroke-dasharray="4" />
                    <line x1="0" y1="150" x2="500" y2="150" stroke="var(--border-light)" stroke-dasharray="4" />
                    <line x1="0" y1="200" x2="500" y2="200" stroke="var(--border)" stroke-width="2" />
                    <!-- Path Fill -->
                    <path d="M0,150 C50,130 100,160 150,140 C200,120 250,50 300,70 C350,90 400,110 450,40 C480,0 500,60 500,60 L500,200 L0,200 Z" fill="url(#cGrad)" />
                    <!-- Path Line -->
                    <path d="M0,150 C50,130 100,160 150,140 C200,120 250,50 300,70 C350,90 400,110 450,40 C480,0 500,60 500,60" fill="none" stroke="var(--primary)" stroke-width="4" stroke-linecap="round" />
                    <!-- Dots -->
                    <circle cx="250" cy="50" r="5" fill="var(--primary)" stroke="var(--bg-surface)" stroke-width="2" />
                    <circle cx="450" cy="40" r="5" fill="var(--primary)" stroke="var(--bg-surface)" stroke-width="2" />
                    <circle cx="500" cy="60" r="6" fill="#fff" stroke="var(--primary)" stroke-width="3" />
                </svg>
            </div>

            <div class="market-grid">
                <div class="info-box">
                    <div class="ib-head up"><i class="fa-solid fa-arrow-trend-up"></i> XU HƯỚNG TĂNG</div>
                    <p>Đồng USD đang cho thấy khả năng phục hồi tốt. Dự kiến tỷ giá sẽ tiếp tục tăng nhẹ trong 48 giờ tới do ảnh hưởng từ thị trường quốc tế.</p>
                </div>
                <div class="info-box alert">
                    <div class="ib-head warn"><i class="fa-solid fa-triangle-exclamation"></i> CƠ HỘI GIAO DỊCH</div>
                    <p>Tỷ giá JPY đang ở mức thấp kỷ lục trong 3 tháng qua. Đây là thời điểm VÀNG để đổi tiền nếu bạn có kế hoạch du lịch Nhật Bản sắp tới.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Pairs -->
    <div class="pairs-section">
        <h3 class="pairs-header">Các cặp tiền tệ phổ biến</h3>
        <div class="pairs-grid">
            <div class="pair-card">
                <div class="lbl">EUR / USD</div>
                <div class="val">1.0845</div>
                <div class="chg dn">▼ -0.15%</div>
            </div>
            <div class="pair-card">
                <div class="lbl">GBP / USD</div>
                <div class="val">1.2630</div>
                <div class="chg up">▲ +0.08%</div>
            </div>
            <div class="pair-card">
                <div class="lbl">USD / JPY</div>
                <div class="val">150.82</div>
                <div class="chg up">▲ +0.45%</div>
            </div>
            <div class="pair-card">
                <div class="lbl">AUD / USD</div>
                <div class="val">0.6540</div>
                <div class="chg dn">▼ -0.21%</div>
            </div>
            <div class="pair-card">
                <div class="lbl">USD / CHF</div>
                <div class="val">0.8920</div>
                <div class="chg up">▲ +0.11%</div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Mockup logic for interaction
const inputFrom = document.getElementById('amt-from');
const inputTo = document.getElementById('amt-to');
const rate = 25410;

function formatNumber(num) {
    return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}

function parseNumber(str) {
    return parseFloat(str.replace(/,/g, '')) || 0;
}

inputFrom.addEventListener('input', function(e) {
    // Remove non-digits
    let val = this.value.replace(/[^0-9]/g, '');
    if(!val) { inputTo.value = '0'; return; }
    
    // Format input
    this.value = formatNumber(val);
    
    // Calc result
    const num = parseNumber(this.value);
    inputTo.value = formatNumber(num * rate);
});

function swapCurrencies() {
    const fromCur = document.querySelector('#cur-from span').innerText;
    const fromImg = document.querySelector('#cur-from img').src;
    
    const toCur = document.querySelector('#cur-to span').innerText;
    const toImg = document.querySelector('#cur-to img').src;
    
    document.querySelector('#cur-from span').innerText = toCur;
    document.querySelector('#cur-from img').src = toImg;
    
    document.querySelector('#cur-to span').innerText = fromCur;
    document.querySelector('#cur-to img').src = fromImg;
    
    // Reset values for simplicity
    inputFrom.value = "1,000";
    // Dummy rate logic based on swap
    if(fromCur === 'USD') {
        inputTo.value = "39.35"; // VND to USD roughly
        document.querySelector('.rate-info .highlight').innerText = "1 VND = 0.000039 USD";
    } else {
        inputTo.value = "25,410,000";
        document.querySelector('.rate-info .highlight').innerText = "1 USD = 25,410 VND";
    }
    
    showToast('Đã đảo ngược chiều chuyển đổi', 'info');
}

// Update time
const now = new Date();
document.getElementById('update-time').innerText = `${now.getHours().toString().padStart(2, '0')}:${now.getMinutes().toString().padStart(2, '0')}, Hôm nay`;

// Chart tabs
document.querySelectorAll('.ctab').forEach(tab => {
    tab.addEventListener('click', function() {
        document.querySelectorAll('.ctab').forEach(t => t.classList.remove('active'));
        this.classList.add('active');
        showToast('Đang cập nhật biểu đồ...', 'info', 1000);
    });
});
</script>
@endpush
