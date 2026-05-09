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

/* Currency Modal */
.modal-overlay {
    position: fixed; top: 0; left: 0; width: 100%; height: 100%;
    background: rgba(0,0,0,0.8); backdrop-filter: blur(8px);
    display: none; align-items: center; justify-content: center; z-index: 1000;
    opacity: 0; transition: 0.3s;
}
.modal-overlay.active { display: flex; opacity: 1; }
.currency-modal {
    background: var(--bg-surface); border: 1px solid var(--border);
    width: 90%; max-width: 500px; border-radius: 24px; padding: 24px;
    box-shadow: 0 20px 50px rgba(0,0,0,0.5);
    transform: translateY(20px); transition: 0.3s;
}
.modal-overlay.active .currency-modal { transform: translateY(0); }
.modal-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
.modal-header h3 { font-size: 20px; font-weight: 700; }
.close-modal { cursor: pointer; font-size: 20px; color: var(--text-muted); }
.close-modal:hover { color: #fff; }

.search-box {
    background: rgba(0,0,0,0.2); border: 1px solid var(--border);
    border-radius: 12px; padding: 12px 16px; display: flex; align-items: center; gap: 12px;
    margin-bottom: 16px;
}
.search-box input { background: transparent; border: none; color: #fff; width: 100%; outline: none; }

.currency-list { max-height: 400px; overflow-y: auto; padding-right: 4px; }
.currency-list::-webkit-scrollbar { width: 4px; }
.currency-list::-webkit-scrollbar-thumb { background: var(--border); border-radius: 10px; }

.currency-item {
    display: flex; align-items: center; justify-content: space-between;
    padding: 12px; border-radius: 12px; cursor: pointer; transition: 0.2s;
    margin-bottom: 4px;
}
.currency-item:hover { background: rgba(255,255,255,0.05); }
.currency-item.active { background: rgba(0,212,255,0.1); border: 1px solid rgba(0,212,255,0.2); }
.cur-info { display: flex; align-items: center; gap: 12px; }
.cur-info img { width: 32px; height: 32px; border-radius: 50%; }
.cur-code { font-weight: 700; font-size: 15px; }
.cur-name { font-size: 13px; color: var(--text-muted); }

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

            <button class="btn btn-primary w-full btn-lg" onclick="performConversion()">
                <i class="fa-solid fa-calculator"></i> Tính toán ngay
            </button>

            <!-- History -->
            <div class="history-list">
                <div class="history-header">
                    <h3>Lịch sử của bạn</h3>
                    <a href="#" onclick="event.preventDefault(); showToast('Tính năng đang phát triển', 'info')">Xem tất cả</a>
                </div>

                <div id="db-history">
                    @forelse($history as $item)
                    <div class="hist-item">
                        <div class="left">
                            <div class="hist-icon"><i class="fa-solid fa-clock-rotate-left"></i></div>
                            <div>
                                <div class="pairs">{{ $item->from_currency }} → {{ $item->to_currency }}</div>
                                <div class="time">{{ $item->created_at->diffForHumans() }}</div>
                            </div>
                        </div>
                        <div class="right">
                            <div class="amt-from">{{ number_format($item->amount) }} {{ $item->from_currency }}</div>
                            <div class="amt-to">{{ number_format($item->result) }} {{ $item->to_currency }}</div>
                        </div>
                    </div>
                    @empty
                    <div class="text-muted text-center py-4" style="font-size: 14px;">Chưa có lịch sử quy đổi.</div>
                    @endforelse
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
        <h3 class="pairs-header">Các cặp tiền tệ Đông Nam Á phổ biến</h3>
        <div class="pairs-grid">
            <div class="pair-card">
                <div class="lbl">USD / VND</div>
                <div class="val">25,410</div>
                <div class="chg up">▲ +0.12%</div>
            </div>
            <div class="pair-card">
                <div class="lbl">THB / VND</div>
                <div class="val">692.5</div>
                <div class="chg dn">▼ -0.05%</div>
            </div>
            <div class="pair-card">
                <div class="lbl">SGD / VND</div>
                <div class="val">18,850</div>
                <div class="chg up">▲ +0.21%</div>
            </div>
            <div class="pair-card">
                <div class="lbl">MYR / VND</div>
                <div class="val">5,380</div>
                <div class="chg dn">▼ -0.11%</div>
            </div>
            <div class="pair-card">
                <div class="lbl">IDR / VND</div>
                <div class="val">1.58</div>
                <div class="chg up">▲ +0.08%</div>
            </div>
        </div>
    </div>
    <!-- Currency Selector Modal -->
    <div class="modal-overlay" id="currencyModal">
        <div class="currency-modal">
            <div class="modal-header">
                <h3>Chọn loại tiền tệ</h3>
                <div class="close-modal" onclick="closeModal()"><i class="fa-solid fa-xmark"></i></div>
            </div>
            <div class="search-box">
                <i class="fa-solid fa-magnifying-glass text-muted"></i>
                <input type="text" placeholder="Tìm kiếm quốc gia hoặc mã tiền tệ..." id="curSearch">
            </div>
            <div class="currency-list" id="currencyList">
                <!-- Items injected via JS -->
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Currency Data (Focus on ASEAN)
const currencies = [
    { code: 'VND', name: 'Việt Nam Đồng', flag: 'vn', rate: 1 },
    { code: 'THB', name: 'Thai Baht', flag: 'th', rate: 0.0014 }, // Example rates relative to VND
    { code: 'SGD', name: 'Singapore Dollar', flag: 'sg', rate: 0.000053 },
    { code: 'MYR', name: 'Malaysian Ringgit', flag: 'my', rate: 0.00019 },
    { code: 'IDR', name: 'Indonesian Rupiah', flag: 'id', rate: 0.63 },
    { code: 'PHP', name: 'Philippine Peso', flag: 'ph', rate: 0.0023 },
    { code: 'KHR', name: 'Cambodian Riel', flag: 'kh', rate: 0.16 },
    { code: 'LAK', name: 'Lao Kip', flag: 'la', rate: 0.85 },
    { code: 'MMK', name: 'Myanmar Kyat', flag: 'mm', rate: 0.082 },
    { code: 'BND', name: 'Brunei Dollar', flag: 'bn', rate: 0.000053 },
    { code: 'USD', name: 'US Dollar', flag: 'us', rate: 0.000039 },
    { code: 'EUR', name: 'Euro', flag: 'eu', rate: 0.000036 },
    { code: 'JPY', name: 'Japanese Yen', flag: 'jp', rate: 0.0061 },
];

let activeSelector = null;
let currentFrom = 'USD';
let currentTo = 'VND';

// Mock rates from a base (VND)
// 1 USD = 25,410 VND
// 1 THB = 690 VND
// 1 SGD = 18,800 VND
// 1 MYR = 5,400 VND
// ...
const baseRates = {
    'VND': 1,
    'USD': 25410,
    'THB': 692.5,
    'SGD': 18850,
    'MYR': 5380,
    'IDR': 1.58,
    'PHP': 442,
    'KHR': 6.2,
    'LAK': 1.18,
    'MMK': 12.1,
    'BND': 18850,
    'EUR': 27500,
    'JPY': 165
};

const inputFrom = document.getElementById('amt-from');
const inputTo = document.getElementById('amt-to');
const curFromBtn = document.getElementById('cur-from');
const curToBtn = document.getElementById('cur-to');
const modal = document.getElementById('currencyModal');
const searchInput = document.getElementById('curSearch');

function formatNumber(num) {
    if (num < 0.01 && num > 0) return num.toFixed(6);
    if (num < 1 && num > 0) return num.toFixed(4);
    return Math.round(num).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}

function parseNumber(str) {
    return parseFloat(str.replace(/,/g, '')) || 0;
}

function updateConversion() {
    const amount = parseNumber(inputFrom.value);
    const rateFrom = baseRates[currentFrom];
    const rateTo = baseRates[currentTo];
    
    // Amount in VND = amount * rateFrom
    // Amount in To = (Amount in VND) / rateTo
    const result = (amount * rateFrom) / rateTo;
    
    inputTo.value = formatNumber(result);
    
    // Update rate info text
    let displayRate = rateFrom / rateTo;
    let baseAmount = 1;
    let baseCode = currentFrom;

    if (currentFrom === 'VND' && displayRate < 0.1) {
        baseAmount = 1000;
        displayRate = (baseAmount * rateFrom) / rateTo;
    }

    document.querySelector('.rate-info .highlight').innerText = `${baseAmount.toLocaleString()} ${baseCode} = ${formatNumber(displayRate)} ${currentTo}`;
    
    // Update chart title (mock)
    const chartTitle = document.querySelector('.ch-left h2');
    if(chartTitle) chartTitle.innerText = `${currentFrom} / ${currentTo}`;
    
    const chartVal = document.querySelector('.ch-left .val');
    if(chartVal) chartVal.innerText = formatNumber(displayRate);
}

inputFrom.addEventListener('input', function() {
    let val = this.value.replace(/[^0-9.]/g, '');
    if(val.split('.').length > 2) val = val.substring(0, val.lastIndexOf('.'));
    
    const num = parseFloat(val) || 0;
    this.value = num.toLocaleString('en-US');
    updateConversion();
});

function openModal(selector) {
    activeSelector = selector;
    modal.classList.add('active');
    renderCurrencies();
}

function closeModal() {
    modal.classList.remove('active');
}

function selectCurrency(code) {
    const cur = currencies.find(c => c.code === code);
    if (activeSelector === 'from') {
        currentFrom = code;
        curFromBtn.querySelector('span').innerText = code;
        curFromBtn.querySelector('img').src = `https://flagcdn.com/w40/${cur.flag}.png`;
    } else {
        currentTo = code;
        curToBtn.querySelector('span').innerText = code;
        curToBtn.querySelector('img').src = `https://flagcdn.com/w40/${cur.flag}.png`;
    }
    closeModal();
    updateConversion();
}

function renderCurrencies(filter = '') {
    const container = document.getElementById('currencyList');
    container.innerHTML = '';
    
    const filtered = currencies.filter(c => 
        c.code.toLowerCase().includes(filter.toLowerCase()) || 
        c.name.toLowerCase().includes(filter.toLowerCase())
    );
    
    filtered.forEach(cur => {
        const isActive = (activeSelector === 'from' && currentFrom === cur.code) || 
                         (activeSelector === 'to' && currentTo === cur.code);
                         
        const item = document.createElement('div');
        item.className = `currency-item ${isActive ? 'active' : ''}`;
        item.onclick = () => selectCurrency(cur.code);
        item.innerHTML = `
            <div class="cur-info">
                <img src="https://flagcdn.com/w40/${cur.flag}.png" alt="${cur.code}">
                <div>
                    <div class="cur-code">${cur.code}</div>
                    <div class="cur-name">${cur.name}</div>
                </div>
            </div>
            ${isActive ? '<i class="fa-solid fa-check text-primary"></i>' : ''}
        `;
        container.appendChild(item);
    });
}

searchInput.addEventListener('input', (e) => {
    renderCurrencies(e.target.value);
});

curFromBtn.onclick = () => openModal('from');
curToBtn.onclick = () => openModal('to');

function swapCurrencies() {
    const temp = currentFrom;
    currentFrom = currentTo;
    currentTo = temp;
    
    const curF = currencies.find(c => c.code === currentFrom);
    const curT = currencies.find(c => c.code === currentTo);
    
    curFromBtn.querySelector('span').innerText = currentFrom;
    curFromBtn.querySelector('img').src = `https://flagcdn.com/w40/${curF.flag}.png`;
    
    curToBtn.querySelector('span').innerText = currentTo;
    curToBtn.querySelector('img').src = `https://flagcdn.com/w40/${curT.flag}.png`;
    
    updateConversion();
    showToast('Đã đảo ngược chiều chuyển đổi', 'info');
}

function performConversion() {
    updateConversion();
    
    const amount = parseNumber(inputFrom.value);
    const result = parseNumber(inputTo.value);
    const rateFrom = baseRates[currentFrom];
    const rateTo = baseRates[currentTo];
    const rate = rateFrom / rateTo;

    // Save to Database via AJAX
    fetch('{{ route("rates.store") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            from_currency: currentFrom,
            to_currency: currentTo,
            amount: amount,
            result: result,
            rate: rate
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast('Đã lưu quy đổi vào lịch sử!', 'success');
            // Refresh history list (simplified: just prepend the new item)
            const historyList = document.getElementById('db-history');
            const emptyMsg = historyList.querySelector('.text-center');
            if(emptyMsg) emptyMsg.remove();

            const newItem = document.createElement('div');
            newItem.className = 'hist-item';
            newItem.style.animation = 'fadeIn 0.5s ease-out';
            newItem.innerHTML = `
                <div class="left">
                    <div class="hist-icon"><i class="fa-solid fa-clock-rotate-left"></i></div>
                    <div>
                        <div class="pairs">${currentFrom} → ${currentTo}</div>
                        <div class="time">Vừa xong</div>
                    </div>
                </div>
                <div class="right">
                    <div class="amt-from">${inputFrom.value} ${currentFrom}</div>
                    <div class="amt-to">${inputTo.value} ${currentTo}</div>
                </div>
            `;
            historyList.insertBefore(newItem, historyList.firstChild);
        } else {
            showToast(data.message || 'Lỗi khi lưu lịch sử', 'warning');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Không thể kết nối máy chủ', 'danger');
    });
}

// Initial update
updateConversion();

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
