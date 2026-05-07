@extends('layouts.app')
@section('title', 'Bản đồ Địa điểm')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" crossorigin=""/>
<style>
/* Remove margin from body to make map full height */
.map-page {
    display: flex;
    height: calc(100vh - var(--navbar-height));
    width: 100%;
    overflow: hidden;
}

/* SIDEBAR */
.map-sidebar {
    width: 420px;
    background: rgba(6, 12, 26, 0.95);
    backdrop-filter: blur(20px);
    border-right: 1px solid var(--border);
    display: flex; flex-direction: column;
    z-index: 10;
    flex-shrink: 0;
}
.sidebar-head {
    padding: 24px;
    border-bottom: 1px solid var(--border-light);
}
.sidebar-head h2 { font-size: 24px; font-weight: 800; margin-bottom: 20px; }

.search-box {
    position: relative; margin-bottom: 16px;
}
.search-box i {
    position: absolute; left: 16px; top: 50%; transform: translateY(-50%);
    color: var(--text-muted);
}
.search-box input {
    width: 100%; padding: 12px 16px 12px 44px;
    background: rgba(255,255,255,0.05); border: 1px solid var(--border);
    border-radius: 12px; color: var(--text-main); outline: none;
    transition: 0.3s;
}
.search-box input:focus { border-color: var(--primary); background: rgba(0,0,0,0.4); }

.filters { display: flex; gap: 8px; flex-wrap: wrap; }
.filter-tag {
    padding: 6px 14px; border-radius: 20px; font-size: 12px; font-weight: 600;
    border: 1px solid var(--border); cursor: pointer; transition: 0.2s;
    display: inline-flex; align-items: center; gap: 6px; color: var(--text-muted);
}
.filter-tag:hover { border-color: var(--text-muted); color: var(--text-main); }
.filter-tag.active { background: rgba(0,212,255,0.1); border-color: var(--primary); color: var(--primary); }

.locations-list {
    flex: 1; overflow-y: auto; padding: 20px;
}
.locations-list::-webkit-scrollbar { width: 6px; }
.locations-list::-webkit-scrollbar-thumb { background: var(--border); border-radius: 10px; }

.loc-card {
    background: var(--bg-surface); border: 1px solid var(--border);
    border-radius: 16px; padding: 20px; margin-bottom: 16px;
    cursor: pointer; transition: 0.3s; position: relative; overflow: hidden;
}
.loc-card:hover { border-color: var(--primary); transform: translateX(4px); }
.loc-card.active { border-color: var(--primary); background: rgba(0,212,255,0.05); }
.loc-card.active::before {
    content: ''; position: absolute; left: 0; top: 0; bottom: 0;
    width: 4px; background: var(--primary);
}

.loc-head { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 8px; }
.loc-title { font-size: 16px; font-weight: 700; color: var(--text-main); display: flex; align-items: center; gap: 8px; }
.loc-title i { color: var(--gold); font-size: 14px; }
.loc-rate { font-size: 18px; font-weight: 800; color: var(--primary); }

.loc-addr { font-size: 13px; color: var(--text-muted); margin-bottom: 16px; line-height: 1.5; }

.loc-foot { display: flex; justify-content: space-between; align-items: center; font-size: 12px; }
.loc-meta { display: flex; gap: 16px; color: var(--text-muted); font-weight: 600; }
.loc-meta span { display: flex; align-items: center; gap: 6px; }
.loc-meta i { color: var(--primary); }

/* MAP AREA */
.map-container { flex: 1; position: relative; z-index: 1; }
#map { width: 100%; height: 100%; background: #060c1a; }
.leaflet-tile { filter: invert(100%) hue-rotate(180deg) brightness(95%) contrast(90%); }

/* CUSTOM MARKERS */
.custom-marker {
    display: flex; flex-direction: column; align-items: center;
    transform: translate(-50%, -100%); transition: 0.3s;
}
.cm-bubble {
    background: var(--bg-surface); border: 2px solid var(--border);
    padding: 6px 12px; border-radius: 12px; font-weight: 800; font-size: 14px;
    color: var(--text-main); margin-bottom: 6px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.5);
    white-space: nowrap; position: relative;
    transition: 0.3s;
}
.cm-bubble::after {
    content: ''; position: absolute; bottom: -6px; left: 50%; transform: translateX(-50%);
    border-width: 6px 6px 0; border-style: solid; border-color: var(--border) transparent transparent transparent;
}
.cm-dot {
    width: 14px; height: 14px; background: var(--text-muted); border: 2px solid var(--bg-color);
    border-radius: 50%; box-shadow: 0 0 10px rgba(0,0,0,0.5); transition: 0.3s;
}
.custom-marker.active .cm-bubble { background: var(--primary); border-color: var(--primary); color: #000; }
.custom-marker.active .cm-bubble::after { border-top-color: var(--primary); }
.custom-marker.active .cm-dot { background: var(--accent); border-color: #000; box-shadow: 0 0 15px var(--accent-glow); }

/* INFO PANEL OVERLAY */
.info-panel {
    position: absolute; top: 24px; right: 24px;
    width: 320px; background: rgba(13,21,38,0.95);
    backdrop-filter: blur(10px); border: 1px solid var(--border-light);
    border-radius: 20px; padding: 24px;
    box-shadow: 0 10px 40px rgba(0,0,0,0.6);
    z-index: 1000; transform: translateX(120%); transition: 0.4s cubic-bezier(0.16, 1, 0.3, 1);
}
.info-panel.visible { transform: translateX(0); }
.ip-close {
    position: absolute; top: 16px; right: 16px;
    width: 30px; height: 30px; border-radius: 50%;
    background: rgba(255,255,255,0.05); display: flex; align-items: center; justify-content: center;
    cursor: pointer; color: var(--text-muted); transition: 0.2s;
}
.ip-close:hover { background: rgba(255,255,255,0.1); color: var(--text-main); }

.ip-title { font-size: 20px; font-weight: 800; margin-bottom: 8px; padding-right: 30px; }
.ip-addr { font-size: 13px; color: var(--text-muted); line-height: 1.5; margin-bottom: 20px; }
.ip-stat-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 24px; }
.ip-stat { background: rgba(0,0,0,0.3); border: 1px solid var(--border); padding: 12px; border-radius: 12px; }
.ip-stat .lbl { font-size: 11px; color: var(--text-muted); text-transform: uppercase; margin-bottom: 4px; }
.ip-stat .val { font-size: 16px; font-weight: 700; color: var(--primary); }

@media (max-width: 900px) {
    .map-page { flex-direction: column; }
    .map-sidebar { width: 100%; height: 50%; border-right: none; border-bottom: 1px solid var(--border); }
    .map-container { height: 50%; }
    .info-panel { top: auto; bottom: 24px; right: 5%; left: 5%; width: 90%; transform: translateY(120%); }
    .info-panel.visible { transform: translateY(0); }
}
</style>
@endpush

@section('content')
<div class="map-page">
    <!-- Sidebar -->
    <div class="map-sidebar">
        <div class="sidebar-head">
            <h2>Tìm Điểm Đổi Tiền</h2>
            <div class="search-box">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input type="text" placeholder="Tìm kiếm khu vực, tên quầy..." id="search-input">
            </div>
            <div class="filters">
                <div class="filter-tag active"><i class="fa-solid fa-fire"></i> Tỷ giá tốt nhất</div>
                <div class="filter-tag"><i class="fa-regular fa-clock"></i> Đang mở cửa</div>
                <div class="filter-tag"><i class="fa-solid fa-building-columns"></i> Ngân hàng</div>
            </div>
        </div>

        <div class="locations-list" id="loc-list">
            <div class="flex justify-between items-center mb-4 text-xs text-muted">
                <span>HIỂN THỊ <strong class="text-primary">5</strong> KẾT QUẢ GẦN NHẤT</span>
                <i class="fa-solid fa-sliders" style="cursor:pointer"></i>
            </div>
            <!-- Populated by JS -->
        </div>
    </div>

    <!-- Map -->
    <div class="map-container">
        <div id="map"></div>

        <!-- Info Overlay -->
        <div class="info-panel" id="info-panel">
            <div class="ip-close" onclick="closePanel()"><i class="fa-solid fa-xmark"></i></div>
            <h3 class="ip-title" id="ip-name">Tên Điểm</h3>
            <p class="ip-addr" id="ip-addr">Địa chỉ chi tiết</p>

            <div class="ip-stat-grid">
                <div class="ip-stat">
                    <div class="lbl">Tỷ giá mua USD</div>
                    <div class="val" id="ip-rate">--</div>
                </div>
                <div class="ip-stat">
                    <div class="lbl">Khoảng cách</div>
                    <div class="val" id="ip-dist" style="color:var(--text-main)">--</div>
                </div>
            </div>

            <button class="btn btn-primary w-full" onclick="showToast('Đang mở Google Maps...', 'info')">
                <i class="fa-solid fa-location-arrow"></i> Chỉ đường ngay
            </button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" crossorigin=""></script>
<script>
// Mock Data
const db = [
    { id: 1, name: 'Hưng Long Exchange', addr: '36 Mạc Thị Bưởi, Quận 1, TP.HCM', rate: '25,410', lat: 10.7745, lng: 106.7042, dist: '0.4 km', star: '4.9', rev: '1.2k', ver: true },
    { id: 2, name: 'Quầy Thu Đổi 59', addr: '135 Đồng Khởi, Quận 1, TP.HCM', rate: '25,405', lat: 10.7760, lng: 106.7030, dist: '0.6 km', star: '4.7', rev: '850', ver: true },
    { id: 3, name: 'Vietcombank - CN Bến Thành', addr: 'Bến Chương Dương, Quận 1', rate: '25,385', lat: 10.7688, lng: 106.7020, dist: '1.2 km', star: '4.8', rev: '2k+', ver: false },
    { id: 4, name: 'Kim Châu Jewelry', addr: 'Chợ Bến Thành, Quận 1', rate: '25,420', lat: 10.7725, lng: 106.6980, dist: '0.8 km', star: '4.5', rev: '430', ver: true },
    { id: 5, name: 'Minh Phát Exchange', addr: 'Lê Thánh Tôn, Quận 1', rate: '25,390', lat: 10.7780, lng: 106.7000, dist: '1.5 km', star: '4.6', rev: '600', ver: false }
];

// Initialize Map
const map = L.map('map', { zoomControl: false }).setView([10.7730, 106.7020], 15);
L.control.zoom({ position: 'bottomright' }).addTo(map);
L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', {
    attribution: '&copy; OpenStreetMap &copy; CARTO'
}).addTo(map);

// DOM Elements
const listEl = document.getElementById('loc-list');
const panelEl = document.getElementById('info-panel');
let markers = {};
let activeId = null;

function closePanel() {
    panelEl.classList.remove('visible');
    activeId = null;
    updateStyles();
}

function selectLoc(id) {
    const loc = db.find(l => l.id === id);
    if(!loc) return;
    
    activeId = id;
    map.flyTo([loc.lat, loc.lng], 16, { duration: 1.5 });
    
    // Update Panel
    document.getElementById('ip-name').innerText = loc.name;
    document.getElementById('ip-addr').innerText = loc.addr;
    document.getElementById('ip-rate').innerText = loc.rate;
    document.getElementById('ip-dist').innerText = loc.dist;
    panelEl.classList.add('visible');
    
    updateStyles();
}

function updateStyles() {
    // List Cards
    document.querySelectorAll('.loc-card').forEach(el => {
        if(el.dataset.id == activeId) el.classList.add('active');
        else el.classList.remove('active');
    });
    
    // Markers
    Object.keys(markers).forEach(k => {
        const id = parseInt(k);
        const loc = db.find(l => l.id === id);
        const icon = markers[id].getIcon();
        const isActive = (id === activeId);
        
        icon.options.html = `
            <div class="custom-marker ${isActive ? 'active' : ''}">
                <div class="cm-bubble">${loc.rate}</div>
                <div class="cm-dot"></div>
            </div>
        `;
        markers[id].setIcon(icon);
    });
}

// Render Data
db.forEach(loc => {
    // Render Marker
    const icon = L.divIcon({
        className: '',
        html: `
            <div class="custom-marker">
                <div class="cm-bubble">${loc.rate}</div>
                <div class="cm-dot"></div>
            </div>
        `,
        iconSize: [60, 50],
        iconAnchor: [30, 50]
    });
    
    const marker = L.marker([loc.lat, loc.lng], {icon}).addTo(map);
    marker.on('click', () => selectLoc(loc.id));
    markers[loc.id] = marker;
    
    // Render List Item
    const card = document.createElement('div');
    card.className = 'loc-card';
    card.dataset.id = loc.id;
    card.onclick = () => selectLoc(loc.id);
    
    const badge = loc.ver ? '<i class="fa-solid fa-shield-check" title="Đã xác thực"></i>' : '';
    
    card.innerHTML = `
        <div class="loc-head">
            <div class="loc-title">${loc.name} ${badge}</div>
            <div class="loc-rate">${loc.rate}</div>
        </div>
        <div class="loc-addr">${loc.addr}</div>
        <div class="loc-foot">
            <div class="loc-meta">
                <span><i class="fa-solid fa-location-dot"></i> ${loc.dist}</span>
                <span><i class="fa-solid fa-star"></i> ${loc.star} (${loc.rev})</span>
            </div>
            <span style="color:var(--primary);font-weight:600">USD</span>
        </div>
    `;
    listEl.appendChild(card);
});
</script>
@endpush
