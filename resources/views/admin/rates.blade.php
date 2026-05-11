@extends('admin.layout')

@section('title', 'Quản Lý Tỷ Giá')
@section('header_title', 'Tỷ Giá')

@push('styles')
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
    <style>
      :root {
        --lime: #65A30D;
        --lime-bg: #F7FEE7;
        --lime-mid: #BEF264;
      }

      /* ── TAB RAIL ── */
      .tab-rail {
        display: flex;
        gap: 2px;
        background: var(--bg-surface);
        border: 1px solid var(--border);
        border-radius: var(--radius-lg);
        padding: 4px;
        margin-bottom: 20px;
        width: fit-content;
      }
      .trb {
        padding: 8px 16px;
        border-radius: 10px;
        border: none;
        background: transparent;
        color: var(--txt3);
        font-size: 13px;
        font-weight: 500;
        font-family: 'DM Sans', sans-serif;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 7px;
        transition: all 0.15s;
        white-space: nowrap;
      }
      .trb.active {
        background: var(--accent);
        color: white;
        box-shadow: var(--shadow-sm);
      }
      .trb:hover:not(.active) {
        background: var(--bg-hover);
        color: var(--txt2);
      }
      .trb i { font-size: 12px; }

      /* ── TAB CONTENT ── */
      .tab-content { display: none; }
      .tab-content.active { display: block; }

      /* ── RATES STAT CARDS ── */
      .rates-stats {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 14px;
        margin-bottom: 20px;
      }

      /* ── DATA TABLE (rates) ── */
      .flag-img {
        width: 32px;
        height: 22px;
        border-radius: 4px;
        object-fit: cover;
        border: 1px solid var(--border);
        flex-shrink: 0;
      }

      .currency-code {
        font-family: 'DM Mono', monospace;
        font-size: 14px;
        font-weight: 600;
        color: var(--accent);
        letter-spacing: .03em;
      }

      .currency-name {
        font-size: 11px;
        color: var(--txt3);
        margin-top: 1px;
      }

      .rate-val {
        font-family: 'DM Mono', monospace;
        font-size: 13.5px;
        font-weight: 600;
        color: var(--txt);
      }

      .change-pill {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 3px 9px;
        border-radius: 100px;
        font-size: 11px;
        font-weight: 700;
        font-family: 'DM Mono', monospace;
      }
      .change-pill.up   { background: var(--success-bg); color: var(--success); }
      .change-pill.down { background: var(--danger-bg);  color: var(--danger); }

      /* Toggle */
      .toggle { position: relative; display: inline-block; width: 38px; height: 21px; }
      .toggle input { opacity: 0; width: 0; height: 0; }
      .toggle-track {
        position: absolute; inset: 0;
        background: var(--border2);
        border-radius: 21px;
        cursor: pointer;
        transition: .2s;
      }
      .toggle input:checked + .toggle-track { background: var(--accent); }
      .toggle-track::before {
        content: '';
        position: absolute;
        width: 15px; height: 15px;
        left: 3px; top: 3px;
        background: white;
        border-radius: 50%;
        transition: .2s;
        box-shadow: var(--shadow-xs);
      }
      .toggle input:checked + .toggle-track::before { transform: translateX(17px); }

      /* ── PANEL ── */
      .panel {
        background: var(--bg-surface);
        border: 1px solid var(--border);
        border-radius: var(--radius-lg);
        overflow: hidden;
      }
      .panel-head {
        display: flex; align-items: center; justify-content: space-between;
        padding: 16px 22px; border-bottom: 1px solid var(--border); gap: 12px; flex-wrap: wrap;
      }
      .panel-head h3 { font-size: 14px; font-weight: 600; color: var(--txt); }
      .panel-body { padding: 22px; }

      /* ── CHART LAYOUT ── */
      .chart-layout { display: grid; grid-template-columns: 240px 1fr; gap: 16px; }
      .chart-type-btns { display: flex; flex-direction: column; gap: 6px; }
      .ctb {
        display: flex; align-items: center; gap: 10px;
        padding: 9px 13px; border-radius: var(--radius-md);
        border: 1px solid var(--border); background: transparent;
        color: var(--txt2); font-size: 13px; font-weight: 500;
        cursor: pointer; transition: .15s; font-family: 'DM Sans', sans-serif; text-align: left;
      }
      .ctb.active, .ctb:hover {
        background: var(--accent-light); color: var(--accent);
        border-color: var(--accent-mid);
      }
      .ctb i { width: 16px; text-align: center; }

      .time-pills { display: flex; gap: 4px; }
      .time-pill {
        flex: 1; padding: 6px; text-align: center;
        border-radius: var(--radius-sm); border: 1px solid var(--border);
        background: transparent; color: var(--txt3); font-size: 12px; font-weight: 600;
        cursor: pointer; transition: .15s; font-family: 'DM Sans', sans-serif;
      }
      .time-pill.active, .time-pill:hover {
        background: var(--accent); color: white; border-color: var(--accent);
      }

      /* ── NEWS CARDS ── */
      .news-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
      .news-card {
        background: var(--bg-surface); border: 1px solid var(--border);
        border-radius: var(--radius-lg); padding: 18px 20px;
        transition: box-shadow .2s, border-color .2s;
        position: relative; overflow: hidden;
      }
      .news-card:hover { box-shadow: var(--shadow-sm); border-color: var(--border2); }
      .nc-bar { position: absolute; left: 0; top: 0; bottom: 0; width: 3px; }
      .nc-bar-info { background: var(--info); }
      .nc-bar-warn { background: var(--danger); }
      .news-card h4 {
        font-family: 'DM Serif Display', serif; font-size: 17px; font-weight: 400;
        color: var(--txt); margin-bottom: 8px; padding-left: 14px; line-height: 1.35;
      }
      .news-card p { font-size: 13px; color: var(--txt2); line-height: 1.65; padding-left: 14px; }
      .nc-meta { display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px; padding-left: 14px; }
      .nc-time { font-size: 11px; color: var(--txt3); font-family: 'DM Mono', monospace; }

      /* ── API / CMS ── */
      .api-steps { display: flex; flex-direction: column; gap: 24px; }
      .api-step { display: flex; gap: 16px; align-items: flex-start; }
      .step-num {
        width: 32px; height: 32px; border-radius: 50%;
        background: var(--accent); color: white;
        font-size: 14px; font-weight: 700;
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0; font-family: 'DM Mono', monospace;
      }
      .step-body h5 { font-size: 14px; font-weight: 600; color: var(--txt); margin-bottom: 5px; }
      .step-body p  { font-size: 13px; color: var(--txt2); line-height: 1.6; }
      .step-body a  { color: var(--accent); text-decoration: none; font-weight: 600; }
      .step-body a:hover { text-decoration: underline; }

      .code-block {
        display: flex; align-items: center; justify-content: space-between;
        background: var(--bg-page); border: 1px solid var(--border);
        border-radius: var(--radius-md); padding: 11px 16px; margin-top: 10px;
        font-family: 'DM Mono', monospace; font-size: 13px; color: var(--accent);
      }
      .copy-btn {
        background: transparent; border: none; color: var(--txt3);
        cursor: pointer; transition: color .2s; font-size: 13px; padding: 2px 6px;
      }
      .copy-btn:hover { color: var(--accent); }

      .info-box {
        background: var(--info-bg); border: 1px solid #BAE6FD;
        border-radius: var(--radius-lg); padding: 18px 20px;
      }
      .info-box h5 {
        font-size: 13px; font-weight: 700; color: var(--info);
        margin-bottom: 12px; display: flex; align-items: center; gap: 7px;
      }
      .info-box ul { padding-left: 16px; display: flex; flex-direction: column; gap: 8px; }
      .info-box li { font-size: 12px; color: var(--txt2); line-height: 1.5; }
      .info-box code {
        background: rgba(2,132,199,.1); color: var(--info);
        padding: 1px 5px; border-radius: 4px;
        font-family: 'DM Mono', monospace; font-size: 11px;
      }

      .settings-form label {
        display: block; font-size: 11px; font-weight: 700; color: var(--txt3);
        letter-spacing: .07em; text-transform: uppercase; margin-bottom: 7px;
      }
      .settings-form .fc {
        width: 100%; background: var(--bg-page); border: 1px solid var(--border);
        border-radius: var(--radius-md); padding: 10px 13px;
        color: var(--txt); font-size: 13px; font-family: 'DM Sans', sans-serif;
        outline: none; transition: .2s;
      }
      .settings-form .fc:focus {
        border-color: var(--accent-mid); background: var(--bg-surface);
        box-shadow: 0 0 0 3px rgba(79,70,229,.08);
      }
      .settings-form textarea.fc { resize: vertical; line-height: 1.6; }

      .divider { height: 1px; background: var(--border); margin: 16px 0; }

      @media (max-width: 1100px) {
        .rates-stats { grid-template-columns: 1fr 1fr; }
        .chart-layout { grid-template-columns: 1fr; }
        .news-grid { grid-template-columns: 1fr; }
      }
    </style>
@endpush

@section('content')

    {{-- ── STAT CARDS ── --}}
    <div class="rates-stats">

      <div class="premium-card" style="border-top:3px solid var(--success);">
        <div class="text-muted" style="font-size:11px;font-weight:700;letter-spacing:.07em;text-transform:uppercase;margin-bottom:10px;">
          Ổn định nhất
          <span class="badge badge-success" style="float:right;font-size:9px;">SAFE</span>
        </div>
        <div class="stat-value" style="font-size:28px;">{{ $mostStable->currency_code ?? 'N/A' }}</div>
        <div style="font-size:12px;margin-top:6px;" class="{{ ($mostStable->change_percentage ?? 0) >= 0 ? 'change-up' : 'change-down' }}">
          <i class="fas fa-caret-{{ ($mostStable->change_percentage ?? 0) >= 0 ? 'up' : 'down' }}"></i>
          {{ abs($mostStable->change_percentage ?? 0) }}% trong 24h
        </div>
      </div>

      <div class="premium-card" style="border-top:3px solid var(--danger);">
        <div class="text-muted" style="font-size:11px;font-weight:700;letter-spacing:.07em;text-transform:uppercase;margin-bottom:10px;">
          Biến động mạnh
          <span class="badge badge-danger" style="float:right;font-size:9px;">HOT</span>
        </div>
        <div class="stat-value" style="font-size:28px;">{{ $mostVolatile->currency_code ?? 'N/A' }}</div>
        <div style="font-size:12px;margin-top:6px;" class="{{ ($mostVolatile->change_percentage ?? 0) >= 0 ? 'change-up' : 'change-down' }}">
          <i class="fas fa-caret-{{ ($mostVolatile->change_percentage ?? 0) >= 0 ? 'up' : 'down' }}"></i>
          {{ abs($mostVolatile->change_percentage ?? 0) }}% trong 24h
        </div>
      </div>

      <div class="premium-card" style="border-top:3px solid var(--accent);">
        <div class="text-muted" style="font-size:11px;font-weight:700;letter-spacing:.07em;text-transform:uppercase;margin-bottom:10px;">Tổng tiền tệ</div>
        <div class="stat-value" style="font-size:28px;">{{ $totalCurrencies }}</div>
        <div style="font-size:12px;color:var(--txt3);margin-top:6px;">
          <i class="fas fa-globe-asia" style="color:var(--accent);"></i>
          Từ {{ $totalCountries }} quốc gia
        </div>
      </div>

      <div class="premium-card" style="border-top:3px solid var(--warning);">
        <div class="text-muted" style="font-size:11px;font-weight:700;letter-spacing:.07em;text-transform:uppercase;margin-bottom:10px;">Máy chủ</div>
        <div style="font-family:'DM Mono',monospace;font-size:18px;font-weight:600;color:var(--txt);margin-bottom:12px;line-height:1.4;">
          {{ now()->format('H:i:s') }}<br>
          <span style="font-size:13px;color:var(--txt3);">{{ now()->format('d/m/Y') }}</span>
        </div>
        <form action="{{ route('admin.rates.update-rates') }}" method="POST" style="margin:0">
          @csrf
          <button type="submit" class="btn-glow" style="width:100%;justify-content:center;font-size:12px;padding:8px;">
            <i class="fas fa-sync-alt"></i> Cập nhật
          </button>
        </form>
      </div>

    </div>

    {{-- ── TAB RAIL ── --}}
    <div class="tab-rail">
      <button class="trb active" onclick="showTab('rates-list', this)">
        <i class="fas fa-table"></i> Danh sách tỷ giá
      </button>
      <button class="trb" onclick="showTab('market-charts', this)">
        <i class="fas fa-chart-area"></i> Biểu đồ
      </button>
      <button class="trb" onclick="showTab('ai-analysis', this)">
        <i class="fas fa-robot"></i> AI Analysis
      </button>
      <button class="trb" onclick="showTab('page-settings', this)">
        <i class="fas fa-sliders-h"></i> CMS
      </button>
      <button class="trb" onclick="showTab('api-config', this)">
        <i class="fas fa-key"></i> API Config
      </button>
    </div>

    {{-- ══════ TAB 1: RATES LIST ══════ --}}
    <div id="rates-list" class="tab-content active">
      <div class="panel">
        <div class="panel-head">
          <div style="display:flex;gap:10px;align-items:center;flex-wrap:wrap;">
            <div class="header-search" style="width:260px;">
              <i class="fas fa-search"></i>
              <input type="text" id="rateSearch" placeholder="Tìm mã tiền tệ, quốc gia...">
            </div>
            <select class="form-control">
              <option value="">Tất cả khu vực</option>
              <option value="SEA">Đông Nam Á</option>
              <option value="EU">Châu Âu</option>
              <option value="AM">Châu Mỹ</option>
            </select>
          </div>
          <div style="display:flex;gap:8px;">
            <button class="btn-secondary" style="font-size:12px;">
              <i class="fas fa-download"></i> Xuất file
            </button>
            <button class="btn-glow" onclick="showModal('addRateModal')">
              <i class="fas fa-plus"></i> Thêm tiền tệ
            </button>
          </div>
        </div>

        <div style="overflow-x:auto;">
          <table class="premium-table">
            <thead>
              <tr>
                <th>Quốc gia</th>
                <th>Mã · Tên tiền tệ</th>
                <th>Tỷ giá (USD)</th>
                <th>24h</th>
                <th>Trạng thái</th>
                <th style="text-align:right;">Thao tác</th>
              </tr>
            </thead>
            <tbody>
              @foreach($rates as $rate)
                  <tr>
                    <td>
                      <div style="display:flex;align-items:center;gap:11px;">
                        <img class="flag-img"
                             src="https://flagcdn.com/w80/{{ strtolower($rate->flag_icon ?? 'vn') }}.png"
                             alt="{{ $rate->country }}">
                        <span style="font-weight:500;font-size:13.5px;">{{ $rate->country }}</span>
                      </div>
                    </td>
                    <td>
                      <div class="currency-code">{{ $rate->currency_code }}</div>
                      <div class="currency-name">{{ $rate->currency_name }}</div>
                    </td>
                    <td>
                      <span class="rate-val">{{ number_format($rate->rate_to_usd, 4) }}</span>
                    </td>
                    <td>
                      <span class="change-pill {{ $rate->change_percentage >= 0 ? 'up' : 'down' }}">
                        <i class="fas fa-arrow-{{ $rate->change_percentage >= 0 ? 'up' : 'down' }}"></i>
                        {{ abs($rate->change_percentage) }}%
                      </span>
                    </td>
                    <td>
                      <label class="toggle">
                        <input type="checkbox" {{ $rate->status ? 'checked' : '' }}
                               onchange="toggleRateStatus({{ $rate->id }})">
                        <span class="toggle-track"></span>
                      </label>
                    </td>
                    <td>
                      <div style="display:flex;gap:6px;justify-content:flex-end;">
                        <button class="btn-icon-glass" onclick="openEditModal({{ json_encode($rate) }})" title="Chỉnh sửa">
                          <i class="fas fa-pen"></i>
                        </button>
                        <form action="{{ route('admin.rates.delete', $rate->id) }}" method="POST"
                              onsubmit="return confirm('Xóa tiền tệ này?')" style="margin:0;">
                          @csrf @method('DELETE')
                          <button type="submit" class="btn-icon-glass text-danger" title="Xóa">
                            <i class="fas fa-trash-alt"></i>
                          </button>
                        </form>
                      </div>
                    </td>
                  </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>

    {{-- ══════ TAB 2: CHARTS ══════ --}}
    <div id="market-charts" class="tab-content">
      <div class="chart-layout">

        <div class="panel" style="padding:20px;">
          <div style="font-size:11px;font-weight:700;color:var(--txt3);letter-spacing:.08em;text-transform:uppercase;margin-bottom:10px;">Cặp tiền tệ</div>
          <select class="form-control" style="width:100%;">
            <option>USD / VND</option>
            <option>THB / VND</option>
            <option>SGD / VND</option>
            <option>EUR / VND</option>
          </select>

          <div class="divider"></div>

          <div style="font-size:11px;font-weight:700;color:var(--txt3);letter-spacing:.08em;text-transform:uppercase;margin-bottom:10px;">Kiểu biểu đồ</div>
          <div class="chart-type-btns">
            <button class="ctb active"><i class="fas fa-chart-line"></i> Line</button>
            <button class="ctb"><i class="fas fa-chart-area"></i> Area</button>
            <button class="ctb"><i class="fas fa-grip-lines"></i> Candles</button>
          </div>

          <div class="divider"></div>

          <form action="{{ route('admin.rates.update-charts') }}" method="POST" style="margin:0;">
            @csrf
            <button type="submit" class="btn-glow" style="width:100%;justify-content:center;">
              <i class="fas fa-check"></i> Áp dụng
            </button>
          </form>
        </div>

        <div class="panel">
          <div class="panel-head">
            <h3>Preview · Thời gian thực</h3>
            <div class="time-pills">
              <button class="time-pill active">1N</button>
              <button class="time-pill">1T</button>
              <button class="time-pill">1TH</button>
              <button class="time-pill">3TH</button>
            </div>
          </div>
          <div style="padding:20px;">
            <div id="chartPreview" style="height:300px;"></div>
          </div>
        </div>

      </div>
    </div>

    {{-- ══════ TAB 3: AI ANALYSIS ══════ --}}
    <div id="ai-analysis" class="tab-content">
      <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:18px;flex-wrap:wrap;gap:10px;">
        <div>
          <h3 style="font-family:'DM Serif Display',serif;font-size:22px;font-weight:400;color:var(--txt);">
            Bản tin <em style="color:var(--accent);">AI</em> · Phân tích thị trường
          </h3>
          <p style="font-size:12px;color:var(--txt3);margin-top:4px;">Được tổng hợp tự động bởi Gemini AI</p>
        </div>
        <div style="display:flex;gap:8px;">
          <form action="{{ route('admin.rates.update-ai') }}" method="POST" style="margin:0;">
            @csrf
            <button type="submit" class="btn-glow">
              <i class="fas fa-robot"></i> Tạo phân tích
            </button>
          </form>
          <button class="btn-secondary" onclick="showModal('addNewsModal')">
            <i class="fas fa-pen"></i> Soạn thủ công
          </button>
        </div>
      </div>

      <div class="news-grid">
        @foreach($news as $item)
            <div class="news-card">
              <div class="nc-bar {{ $item->type == 'warning' ? 'nc-bar-warn' : 'nc-bar-info' }}"></div>
              <div class="nc-meta">
                <span class="badge {{ $item->type == 'warning' ? 'badge-danger' : 'badge-info' }}">
                  {{ strtoupper($item->type) }}
                </span>
                <span class="nc-time">{{ $item->created_at->diffForHumans() }}</span>
              </div>
              <h4>{{ $item->title }}</h4>
              <p>{{ Str::limit($item->content, 200) }}</p>
              <div style="display:flex;justify-content:flex-end;margin-top:14px;padding-left:14px;">
                <form action="{{ route('admin.rates.news.delete', $item->id) }}" method="POST"
                      onsubmit="return confirm('Xóa bản tin này?')" style="margin:0;">
                  @csrf @method('DELETE')
                  <button type="submit" class="btn-icon-glass text-danger" title="Xóa">
                    <i class="fas fa-trash-alt"></i>
                  </button>
                </form>
              </div>
            </div>
        @endforeach
      </div>
    </div>

    {{-- ══════ TAB 4: PAGE SETTINGS ══════ --}}
    <div id="page-settings" class="tab-content">
      <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:18px;flex-wrap:wrap;gap:12px;">
        <div>
          <h3 style="font-family:'DM Serif Display',serif;font-size:22px;font-weight:400;color:var(--txt);">
            Cấu hình <em style="color:var(--accent);">CMS</em>
          </h3>
          <p style="font-size:12px;color:var(--txt3);margin-top:4px;">Giao diện trang tỷ giá người dùng</p>
        </div>
        <form action="{{ route('admin.rates.update-cms') }}" method="POST" style="margin:0;">
          @csrf
          <button type="submit" class="btn-secondary">
            <i class="fas fa-magic"></i> AI viết nội dung
          </button>
        </form>
      </div>

      <div class="panel">
        <div class="panel-body">
          <form action="{{ route('admin.rates.settings') }}" method="POST" enctype="multipart/form-data" class="settings-form">
            @csrf
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:18px;">
              <div>
                <label>Tiêu đề chính (Hero Title)</label>
                <input type="text" name="hero_title" class="fc" value="{{ $settings->hero_title }}"
                       placeholder="VD: Tỷ giá ngoại tệ hôm nay">
              </div>
              <div>
                <label>Tiền tệ mặc định</label>
                <select name="default_currency" class="fc">
                  <option value="USD" {{ $settings->default_currency == 'USD' ? 'selected' : '' }}>USD — Đô la Mỹ</option>
                  <option value="VND" {{ $settings->default_currency == 'VND' ? 'selected' : '' }}>VND — Việt Nam Đồng</option>
                  <option value="THB" {{ $settings->default_currency == 'THB' ? 'selected' : '' }}>THB — Thai Baht</option>
                </select>
              </div>
              <div style="grid-column:1/-1;">
                <label>Mô tả trang</label>
                <textarea name="hero_description" class="fc" rows="4">{{ $settings->hero_description }}</textarea>
              </div>
              <div>
                <label>Text nút CTA</label>
                <input type="text" name="cta_text" class="fc" value="{{ $settings->cta_text }}">
              </div>
              <div>
                <label>Hình nền Banner</label>
                <input type="file" name="banner_image" class="fc" style="padding:8px 12px;cursor:pointer;">
              </div>
            </div>
            <div style="margin-top:18px;">
              <button type="submit" class="btn-glow">
                <i class="fas fa-save"></i> Lưu thay đổi
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>

    {{-- ══════ TAB 5: API CONFIG ══════ --}}
    <div id="api-config" class="tab-content">
      <div style="display:grid;grid-template-columns:1fr 320px;gap:18px;align-items:start;">
        <div class="panel">
          <div class="panel-head">
            <h3>Hướng dẫn cấu hình API</h3>
            <span class="badge badge-success"><i class="fas fa-circle" style="font-size:7px;"></i> Kết nối</span>
          </div>
          <div class="panel-body">
            <div class="api-steps">
              <div class="api-step">
                <div class="step-num">1</div>
                <div class="step-body">
                  <h5>Lấy API Key</h5>
                  <p>Truy cập <a href="https://aistudio.google.com/app/apikey" target="_blank">Google AI Studio</a>
                     để tạo API Key miễn phí cho Gemini.</p>
                </div>
              </div>
              <div class="api-step">
                <div class="step-num">2</div>
                <div class="step-body">
                  <h5>Cấu hình file .env</h5>
                  <p>Mở file <code style="background:var(--accent-light);color:var(--accent);padding:1px 6px;border-radius:4px;font-family:'DM Mono',monospace;font-size:12px;">.env</code>
                     ở thư mục gốc và thêm dòng sau:</p>
                  <div class="code-block">
                    <span>GEMINI_API_KEY=your_api_key_here</span>
                    <button class="copy-btn" onclick="copyToClipboard('GEMINI_API_KEY=your_api_key_here')" title="Sao chép">
                      <i class="far fa-copy"></i>
                    </button>
                  </div>
                </div>
              </div>
              <div class="api-step">
                <div class="step-num">3</div>
                <div class="step-body">
                  <h5>Kiểm tra kết nối</h5>
                  <p>Sau khi lưu, vào tab <strong style="color:var(--accent);">AI Analysis</strong> và nhấn
                     <strong style="color:var(--accent);">Tạo phân tích</strong> để kiểm tra.</p>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="info-box">
          <h5><i class="fas fa-shield-alt"></i> Lưu ý bảo mật</h5>
          <ul>
            <li>Không chia sẻ file <code>.env</code> hoặc commit lên Git công khai.</li>
            <li>API Key Gemini miễn phí nhưng có giới hạn lượt gọi mỗi phút.</li>
            <li>Nếu API lỗi, hệ thống tự chuyển sang chế độ Demo để tránh downtime.</li>
            <li>Nên rotate API Key định kỳ 3–6 tháng/lần.</li>
          </ul>
        </div>
      </div>
    </div>

@endsection

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/apexcharts/3.45.2/apexcharts.min.js"></script>
    <script>
    let chartInstance = null;

    function showTab(tabId, btn) {
      document.querySelectorAll('.tab-content').forEach(t => t.classList.remove('active'));
      document.querySelectorAll('.trb').forEach(b => b.classList.remove('active'));
      document.getElementById(tabId).classList.add('active');
      btn.classList.add('active');
      if (tabId === 'market-charts') setTimeout(renderChart, 80);
    }

    function toggleRateStatus(id) {
      fetch(`/admin/rates/${id}/toggle`, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json' }
      });
    }

    function copyToClipboard(text) {
      navigator.clipboard.writeText(text).then(() => {
        const btn = event.currentTarget;
        btn.innerHTML = '<i class="fas fa-check"></i>';
        btn.style.color = 'var(--success)';
        setTimeout(() => { btn.innerHTML = '<i class="far fa-copy"></i>'; btn.style.color = ''; }, 1800);
      });
    }

    function renderChart() {
      if (chartInstance) { chartInstance.destroy(); chartInstance = null; }
      const el = document.querySelector('#chartPreview');
      if (!el) return;
      chartInstance = new ApexCharts(el, {
        series: [{ name: 'USD/VND', data: [25280,25310,25395,25360,25420,25480,25445,25500,25460,25530,25510,25560,25490,25600] }],
        chart: {
          height: 300, type: 'area',
          toolbar: { show: false },
          fontFamily: 'DM Mono, monospace',
          background: 'transparent',
        },
        colors: ['#4F46E5'],
        stroke: { curve: 'smooth', width: 2.5 },
        fill: {
          type: 'gradient',
          gradient: { shadeIntensity: 1, opacityFrom: 0.15, opacityTo: 0.01, stops: [0,100] }
        },
        dataLabels: { enabled: false },
        xaxis: {
          categories: ['T2','T3','T4','T5','T6','T7','CN','T2','T3','T4','T5','T6','T7','CN'],
          labels: { style: { colors: '#9298A8', fontSize: '11px' } },
          axisBorder: { show: false }, axisTicks: { show: false }
        },
        yaxis: { labels: { style: { colors: '#9298A8', fontSize: '11px' }, formatter: v => v.toLocaleString() } },
        grid: { borderColor: '#E4E7ED', strokeDashArray: 4 },
        tooltip: { theme: 'light', x: { show: true } }
      });
      chartInstance.render();
    }

    document.querySelectorAll('.ctb').forEach(btn => {
      btn.addEventListener('click', function () {
        document.querySelectorAll('.ctb').forEach(b => b.classList.remove('active'));
        this.classList.add('active');
      });
    });

    document.querySelectorAll('.time-pill').forEach(btn => {
      btn.addEventListener('click', function () {
        document.querySelectorAll('.time-pill').forEach(b => b.classList.remove('active'));
        this.classList.add('active');
      });
    });
    </script>
@endpush