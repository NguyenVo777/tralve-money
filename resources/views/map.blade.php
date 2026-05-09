@extends('layouts.app')
@section('title', 'Bản đồ Địa điểm')

@push('styles')

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
<script src="https://maps.googleapis.com/maps/api/js?key=YOUR_GOOGLE_MAPS_API_KEY&libraries=places"></script>
<script>
let map, placesService, infoWindow;
let markers = [];
const listEl = document.getElementById('loc-list');
const panelEl = document.getElementById('info-panel');
const searchInput = document.getElementById('search-input');

function initMap() {
    const defaultLoc = { lat: 10.7730, lng: 106.7020 }; // HCM City
    map = new google.maps.Map(document.getElementById('map'), {
        center: defaultLoc,
        zoom: 15,
        styles: [
            { elementType: "geometry", stylers: [{ color: "#242f3e" }] },
            { elementType: "labels.text.stroke", stylers: [{ color: "#242f3e" }] },
            { elementType: "labels.text.fill", stylers: [{ color: "#746855" }] },
            {
                featureType: "administrative.locality",
                elementType: "labels.text.fill",
                stylers: [{ color: "#d59563" }],
            },
            {
                featureType: "poi",
                elementType: "labels.text.fill",
                stylers: [{ color: "#d59563" }],
            },
            {
                featureType: "poi.park",
                elementType: "geometry",
                stylers: [{ color: "#263c3f" }],
            },
            {
                featureType: "poi.park",
                elementType: "labels.text.fill",
                stylers: [{ color: "#6b9a76" }],
            },
            {
                featureType: "road",
                elementType: "geometry",
                stylers: [{ color: "#38414e" }],
            },
            {
                featureType: "road",
                elementType: "geometry.stroke",
                stylers: [{ color: "#212a37" }],
            },
            {
                featureType: "road",
                elementType: "labels.text.fill",
                stylers: [{ color: "#9ca5b3" }],
            },
            {
                featureType: "road.highway",
                elementType: "geometry",
                stylers: [{ color: "#746855" }],
            },
            {
                featureType: "road.highway",
                elementType: "geometry.stroke",
                stylers: [{ color: "#1f2835" }],
            },
            {
                featureType: "road.highway",
                elementType: "labels.text.fill",
                stylers: [{ color: "#f3d19c" }],
            },
            {
                featureType: "water",
                elementType: "geometry",
                stylers: [{ color: "#17263c" }],
            },
            {
                featureType: "water",
                elementType: "labels.text.fill",
                stylers: [{ color: "#515c6d" }],
            },
            {
                featureType: "water",
                elementType: "labels.text.stroke",
                stylers: [{ color: "#17263c" }],
            },
        ],
    });

    placesService = new google.maps.places.PlacesService(map);
    
    // Attempt to get user location
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            (position) => {
                const pos = {
                    lat: position.coords.latitude,
                    lng: position.coords.longitude,
                };
                map.setCenter(pos);
                searchPlaces(pos);
            },
            () => { searchPlaces(defaultLoc); }
        );
    } else {
        searchPlaces(defaultLoc);
    }
}

function searchPlaces(location, keyword = 'currency exchange') {
    const request = {
        location: location,
        radius: '5000',
        query: keyword
    };

    placesService.textSearch(request, (results, status) => {
        if (status === google.maps.places.PlacesServiceStatus.OK && results) {
            clearResults();
            results.forEach((place, index) => {
                createMarker(place);
                addToList(place, index);
            });
        }
    });
}

function clearResults() {
    markers.forEach(m => m.setMap(null));
    markers = [];
    listEl.innerHTML = '';
}

function createMarker(place) {
    if (!place.geometry || !place.geometry.location) return;
    const marker = new google.maps.Marker({
        map,
        position: place.geometry.location,
        title: place.name,
        icon: {
            path: google.maps.SymbolPath.CIRCLE,
            scale: 8,
            fillColor: "var(--primary)",
            fillOpacity: 1,
            strokeWeight: 2,
            strokeColor: "#ffffff",
        }
    });

    marker.addListener('click', () => {
        selectLoc(place);
    });

    markers.push(marker);
}

function addToList(place, index) {
    const card = document.createElement('div');
    card.className = 'loc-card';
    card.dataset.id = place.place_id;
    card.onclick = () => selectLoc(place);
    
    const rating = place.rating ? place.rating : 'N/A';
    const reviews = place.user_ratings_total ? place.user_ratings_total : '0';
    const openNow = (place.opening_hours && place.opening_hours.isOpen()) ? 
                    '<span style="color:var(--accent)"><i class="fa-solid fa-clock"></i> Đang mở</span>' : 
                    '<span style="color:var(--text-muted)"><i class="fa-solid fa-clock"></i> Đóng cửa</span>';
    
    card.innerHTML = `
        <div class="loc-head">
            <div class="loc-title">${place.name}</div>
        </div>
        <div class="loc-addr">${place.formatted_address || place.vicinity}</div>
        <div class="loc-foot">
            <div class="loc-meta">
                ${openNow}
                <span><i class="fa-solid fa-star"></i> ${rating} (${reviews})</span>
            </div>
        </div>
    `;
    listEl.appendChild(card);
}

function selectLoc(place) {
    map.panTo(place.geometry.location);
    map.setZoom(17);
    
    // Update Panel
    document.getElementById('ip-name').innerText = place.name;
    document.getElementById('ip-addr').innerText = place.formatted_address || place.vicinity;
    document.getElementById('ip-rate').innerText = 'Tra cứu tại quầy'; // Google doesn't have exchange rates
    document.getElementById('ip-dist').innerText = 'Tính toán...';
    
    panelEl.classList.add('visible');
    
    // Highlight list item
    document.querySelectorAll('.loc-card').forEach(el => {
        if(el.dataset.id === place.place_id) el.classList.add('active');
        else el.classList.remove('active');
    });
}

function closePanel() {
    panelEl.classList.remove('visible');
    document.querySelectorAll('.loc-card').forEach(el => el.classList.remove('active'));
}

searchInput.addEventListener('keypress', function (e) {
    if (e.key === 'Enter') {
        searchPlaces(map.getCenter(), this.value || 'currency exchange');
    }
});

// Load map once script is ready
window.onload = () => {
    // Note: Since we are not using async/defer in script tag due to placeholder key,
    // we initialize it directly if google is available.
    if(typeof google !== 'undefined') {
        initMap();
    } else {
        listEl.innerHTML = '<div style="padding: 20px; color: var(--danger);">Vui lòng thiết lập API Key của Google Maps trong file map.blade.php.</div>';
    }
};
</script>
@endpush
