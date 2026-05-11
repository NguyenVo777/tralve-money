@extends('admin.layout')

@section('title', 'Quản Lý Tỷ Giá ASEAN')
@section('header_title', 'Tỷ Giá ASEAN')

@push('styles')
  <link
    href="https://fonts.googleapis.com/css2?family=IBM+Plex+Mono:wght@400;500;600;700&family=DM+Sans:wght@400;500;600;700&family=Syne:wght@600;700;800&display=swap"
    rel="stylesheet">
  <style>
    /* ── RESET CONTEXT ── */
    * {
      box-sizing: border-box;
    }

    /* ══════════════════════════════════════════
           BASE CURRENCY HERO BANNER
        ══════════════════════════════════════════ */
    .base-hero {
      position: relative;
      background: linear-gradient(135deg, var(--accent) 0%, color-mix(in srgb, var(--accent) 70%, #0f172a) 100%);
      border-radius: 18px;
      padding: 24px 28px;
      margin-bottom: 20px;
      overflow: hidden;
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 20px;
      flex-wrap: wrap;
    }

    /* decorative ring */
    .base-hero::before {
      content: '';
      position: absolute;
      right: -60px;
      top: -60px;
      width: 260px;
      height: 260px;
      border-radius: 50%;
      border: 40px solid rgba(255, 255, 255, 0.06);
      pointer-events: none;
    }

    .base-hero::after {
      content: '';
      position: absolute;
      right: 60px;
      bottom: -80px;
      width: 180px;
      height: 180px;
      border-radius: 50%;
      border: 28px solid rgba(255, 255, 255, 0.04);
      pointer-events: none;
    }

    .bh-identity {
      display: flex;
      align-items: center;
      gap: 18px;
      position: relative;
      z-index: 1;
    }

    .bh-flag-wrap {
      width: 64px;
      height: 64px;
      border-radius: 16px;
      background: rgba(255, 255, 255, 0.15);
      backdrop-filter: blur(8px);
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 36px;
      flex-shrink: 0;
      border: 1px solid rgba(255, 255, 255, 0.2);
    }

    .bh-text .bh-eyebrow {
      font-size: 10px;
      font-weight: 700;
      letter-spacing: .12em;
      text-transform: uppercase;
      color: rgba(255, 255, 255, 0.55);
      margin-bottom: 4px;
      font-family: 'DM Sans', sans-serif;
    }

    .bh-code {
      font-family: 'IBM Plex Mono', monospace;
      font-size: 32px;
      font-weight: 700;
      color: #fff;
      line-height: 1;
    }

    .bh-name {
      font-size: 13px;
      color: rgba(255, 255, 255, 0.65);
      margin-top: 5px;
      font-family: 'DM Sans', sans-serif;
    }

    /* quick rate chips */
    .bh-rates {
      display: flex;
      gap: 6px;
      flex-wrap: wrap;
      position: relative;
      z-index: 1;
    }

    .bh-rate-chip {
      background: rgba(255, 255, 255, 0.12);
      border: 1px solid rgba(255, 255, 255, 0.18);
      border-radius: 12px;
      padding: 9px 14px;
      text-align: center;
      backdrop-filter: blur(6px);
      min-width: 88px;
      transition: background .2s;
    }

    .bh-rate-chip:hover {
      background: rgba(255, 255, 255, 0.2);
    }

    .brc-label {
      font-size: 9px;
      font-weight: 700;
      letter-spacing: .08em;
      color: rgba(255, 255, 255, 0.5);
      text-transform: uppercase;
      margin-bottom: 4px;
      font-family: 'DM Sans', sans-serif;
    }

    .brc-val {
      font-family: 'IBM Plex Mono', monospace;
      font-size: 15px;
      font-weight: 600;
      color: #fff;
      line-height: 1;
    }

    .brc-cur {
      font-size: 10px;
      color: rgba(255, 255, 255, 0.5);
      margin-top: 3px;
      font-family: 'DM Sans', sans-serif;
    }

    .bh-actions {
      display: flex;
      flex-direction: column;
      gap: 8px;
      position: relative;
      z-index: 1;
      flex-shrink: 0;
    }

    .btn-switch-base {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      background: rgba(255, 255, 255, 0.15);
      border: 1px solid rgba(255, 255, 255, 0.25);
      border-radius: 10px;
      padding: 9px 16px;
      color: #fff;
      font-size: 12px;
      font-weight: 600;
      cursor: pointer;
      transition: background .2s;
      font-family: 'DM Sans', sans-serif;
      white-space: nowrap;
    }

    .btn-switch-base:hover {
      background: rgba(255, 255, 255, 0.25);
    }

    /* ══════════════════════════════════════════
           PAGE HEADER ROW
        ══════════════════════════════════════════ */
    .page-header-row {
      display: flex;
      align-items: flex-start;
      justify-content: space-between;
      gap: 16px;
      flex-wrap: wrap;
      margin-bottom: 20px;
    }

    .page-header-row h2 {
      font-family: 'Syne', sans-serif;
      font-size: 22px;
      font-weight: 800;
      color: var(--txt);
      line-height: 1.2;
    }

    .page-header-row p {
      font-size: 12px;
      color: var(--txt3);
      margin-top: 4px;
      font-family: 'DM Sans', sans-serif;
    }

    /* base selector pill in header */
    .base-pill {
      display: inline-flex;
      align-items: center;
      gap: 10px;
      background: var(--accent-light);
      border: 1.5px solid var(--accent-mid);
      border-radius: 12px;
      padding: 8px 16px;
      cursor: pointer;
      transition: border-color .2s;
    }

    .base-pill:hover {
      border-color: var(--accent);
    }

    .base-pill .bp-flag {
      font-size: 22px;
      line-height: 1;
    }

    .base-pill .bp-code {
      font-family: 'IBM Plex Mono', monospace;
      font-size: 16px;
      font-weight: 700;
      color: var(--accent);
    }

    .base-pill .bp-label {
      font-size: 10px;
      color: var(--accent);
      opacity: .65;
      font-family: 'DM Sans', sans-serif;
    }

    .base-pill i {
      font-size: 9px;
      color: var(--accent);
    }

    /* ══════════════════════════════════════════
           STAT CARDS ROW
        ══════════════════════════════════════════ */
    .stats-row {
      display: grid;
      grid-template-columns: repeat(5, 1fr);
      gap: 12px;
      margin-bottom: 20px;
    }

    .stat-card {
      background: var(--bg-surface);
      border: 1px solid var(--border);
      border-radius: 14px;
      padding: 16px 18px;
      position: relative;
      overflow: hidden;
    }

    .stat-card::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      height: 3px;
      border-radius: 14px 14px 0 0;
    }

    .sc-green::before {
      background: var(--success);
    }

    .sc-red::before {
      background: var(--danger);
    }

    .sc-blue::before {
      background: var(--accent);
    }

    .sc-amber::before {
      background: var(--warning);
    }

    .sc-gray::before {
      background: #94a3b8;
    }

    .sc-label {
      font-size: 10px;
      font-weight: 700;
      letter-spacing: .07em;
      text-transform: uppercase;
      color: var(--txt3);
      margin-bottom: 10px;
      font-family: 'DM Sans', sans-serif;
      display: flex;
      align-items: center;
      justify-content: space-between;
    }

    .sc-value {
      font-family: 'IBM Plex Mono', monospace;
      font-size: 26px;
      font-weight: 700;
      color: var(--txt);
      line-height: 1;
    }

    .sc-sub {
      font-size: 11px;
      color: var(--txt3);
      margin-top: 8px;
      font-family: 'DM Sans', sans-serif;
      display: flex;
      align-items: center;
      gap: 4px;
    }

    .sc-sub.up {
      color: var(--success);
    }

    .sc-sub.down {
      color: var(--danger);
    }

    /* ══════════════════════════════════════════
           TAB RAIL
        ══════════════════════════════════════════ */
    .tab-rail {
      display: flex;
      gap: 2px;
      background: var(--bg-surface);
      border: 1px solid var(--border);
      border-radius: 14px;
      padding: 4px;
      margin-bottom: 20px;
      width: fit-content;
    }

    .trb {
      display: inline-flex;
      align-items: center;
      gap: 7px;
      padding: 8px 16px;
      border-radius: 10px;
      border: none;
      background: transparent;
      color: var(--txt3);
      font-size: 12px;
      font-weight: 600;
      font-family: 'DM Sans', sans-serif;
      cursor: pointer;
      transition: all .15s;
      white-space: nowrap;
      letter-spacing: .02em;
    }

    .trb.active {
      background: var(--accent);
      color: #fff;
      box-shadow: 0 2px 8px rgba(0, 0, 0, .15);
    }

    .trb:hover:not(.active) {
      background: var(--bg-hover);
      color: var(--txt2);
    }

    .trb i {
      font-size: 11px;
    }

    /* ══════════════════════════════════════════
           TAB CONTENT
        ══════════════════════════════════════════ */
    .tab-content {
      display: none;
    }

    .tab-content.active {
      display: block;
    }

    /* ══════════════════════════════════════════
           PANEL
        ══════════════════════════════════════════ */
    .panel {
      background: var(--bg-surface);
      border: 1px solid var(--border);
      border-radius: 14px;
      overflow: hidden;
    }

    .panel-head {
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 14px 20px;
      border-bottom: 1px solid var(--border);
      gap: 12px;
      flex-wrap: wrap;
    }

    .panel-head h3 {
      font-size: 13px;
      font-weight: 700;
      color: var(--txt);
      font-family: 'DM Sans', sans-serif;
    }

    .panel-body {
      padding: 20px;
    }

    /* ══════════════════════════════════════════
           ALERT BARS
        ══════════════════════════════════════════ */
    .alert-bar {
      display: flex;
      align-items: flex-start;
      gap: 10px;
      border-radius: 10px;
      padding: 10px 16px;
      font-size: 12px;
      line-height: 1.65;
      margin-bottom: 16px;
      font-family: 'DM Sans', sans-serif;
    }

    .alert-bar i {
      flex-shrink: 0;
      margin-top: 2px;
    }

    .alert-info {
      background: var(--info-bg);
      border: 1px solid #BAE6FD;
      color: var(--info);
    }

    .alert-warn {
      background: var(--warning-bg);
      border: 1px solid rgba(133, 79, 11, .25);
      color: var(--warning);
    }

    /* ══════════════════════════════════════════
           RATES TABLE
        ══════════════════════════════════════════ */
    .flag-img {
      width: 32px;
      height: 22px;
      border-radius: 5px;
      object-fit: cover;
      border: 1px solid var(--border);
      flex-shrink: 0;
    }

    .cur-code {
      font-family: 'IBM Plex Mono', monospace;
      font-size: 14px;
      font-weight: 700;
      color: var(--accent);
      letter-spacing: .03em;
    }

    .cur-name {
      font-size: 11px;
      color: var(--txt3);
      margin-top: 2px;
      font-family: 'DM Sans', sans-serif;
    }

    /* two-line rate cell */
    .rate-primary {
      font-family: 'IBM Plex Mono', monospace;
      font-size: 13px;
      font-weight: 600;
      color: var(--txt);
    }

    .rate-inverse {
      font-family: 'IBM Plex Mono', monospace;
      font-size: 10px;
      color: var(--txt3);
      margin-top: 3px;
    }

    .chg-pill {
      display: inline-flex;
      align-items: center;
      gap: 3px;
      padding: 3px 8px;
      border-radius: 100px;
      font-size: 10px;
      font-weight: 700;
      font-family: 'IBM Plex Mono', monospace;
    }

    .chg-up {
      background: var(--success-bg);
      color: var(--success);
    }

    .chg-down {
      background: var(--danger-bg);
      color: var(--danger);
    }

    /* toggle */
    .toggle {
      position: relative;
      display: inline-block;
      width: 38px;
      height: 21px;
    }

    .toggle input {
      opacity: 0;
      width: 0;
      height: 0;
    }

    .toggle-track {
      position: absolute;
      inset: 0;
      background: var(--border2);
      border-radius: 21px;
      cursor: pointer;
      transition: .2s;
    }

    .toggle input:checked+.toggle-track {
      background: var(--accent);
    }

    .toggle-track::before {
      content: '';
      position: absolute;
      width: 15px;
      height: 15px;
      left: 3px;
      top: 3px;
      background: white;
      border-radius: 50%;
      transition: .2s;
      box-shadow: 0 1px 3px rgba(0, 0, 0, .2);
    }

    .toggle input:checked+.toggle-track::before {
      transform: translateX(17px);
    }

    /* ══════════════════════════════════════════
           CROSS-RATE MATRIX
        ══════════════════════════════════════════ */
    .matrix-wrap {
      overflow-x: auto;
      padding: 20px;
    }

    .matrix-tbl {
      width: 100%;
      border-collapse: collapse;
      font-family: 'IBM Plex Mono', monospace;
    }

    .matrix-tbl th {
      padding: 9px 11px;
      font-size: 10px;
      font-weight: 700;
      letter-spacing: .07em;
      text-align: center;
      background: var(--bg-page);
      color: var(--txt2);
      border: 1px solid var(--border);
      white-space: nowrap;
    }

    .matrix-tbl td {
      padding: 8px 11px;
      text-align: center;
      font-size: 11px;
      color: var(--txt2);
      border: 1px solid var(--border);
      transition: background .1s;
      cursor: default;
    }

    .matrix-tbl td:hover {
      background: var(--accent-light);
      color: var(--accent);
    }

    .mtx-self {
      background: var(--bg-page) !important;
      color: var(--txt3);
      font-size: 10px;
    }

    .mtx-base {
      background: var(--accent-light);
      color: var(--accent);
      font-weight: 700;
    }

    .mtx-th-base {
      background: rgba(var(--accent-rgb, 29, 158, 117), .09) !important;
      color: var(--accent) !important;
    }

    /* ══════════════════════════════════════════
           CHART LAYOUT
        ══════════════════════════════════════════ */
    .chart-layout {
      display: grid;
      grid-template-columns: 220px 1fr;
      gap: 16px;
    }

    .chart-sidebar {
      display: flex;
      flex-direction: column;
      gap: 6px;
    }

    .chart-type-btn {
      display: flex;
      align-items: center;
      gap: 9px;
      padding: 8px 12px;
      border-radius: 10px;
      border: 1px solid var(--border);
      background: transparent;
      color: var(--txt2);
      font-size: 12px;
      font-weight: 500;
      cursor: pointer;
      transition: .15s;
      font-family: 'DM Sans', sans-serif;
      text-align: left;
      width: 100%;
    }

    .chart-type-btn.active,
    .chart-type-btn:hover {
      background: var(--accent-light);
      color: var(--accent);
      border-color: var(--accent-mid);
    }

    .chart-type-btn i {
      width: 14px;
      text-align: center;
      font-size: 11px;
    }

    .time-pills {
      display: flex;
      gap: 4px;
    }

    .time-pill {
      flex: 1;
      padding: 5px 0;
      text-align: center;
      border-radius: 8px;
      border: 1px solid var(--border);
      background: transparent;
      color: var(--txt3);
      font-size: 11px;
      font-weight: 600;
      cursor: pointer;
      transition: .15s;
      font-family: 'DM Sans', sans-serif;
    }

    .time-pill.active,
    .time-pill:hover {
      background: var(--accent);
      color: white;
      border-color: var(--accent);
    }

    .sidebar-label {
      font-size: 10px;
      font-weight: 700;
      color: var(--txt3);
      letter-spacing: .08em;
      text-transform: uppercase;
      margin-bottom: 8px;
      font-family: 'DM Sans', sans-serif;
    }

    .divider {
      height: 1px;
      background: var(--border);
      margin: 14px 0;
    }

    /* ══════════════════════════════════════════
           NEWS / AI CARDS
        ══════════════════════════════════════════ */
    .news-grid {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 14px;
    }

    .news-card {
      background: var(--bg-surface);
      border: 1px solid var(--border);
      border-radius: 14px;
      padding: 18px 20px;
      position: relative;
      overflow: hidden;
      transition: border-color .2s, box-shadow .2s;
    }

    .news-card:hover {
      border-color: var(--border2);
      box-shadow: 0 4px 20px rgba(0, 0, 0, .06);
    }

    .nc-accent-bar {
      position: absolute;
      left: 0;
      top: 0;
      bottom: 0;
      width: 3px;
    }

    .nc-bar-info {
      background: var(--info);
    }

    .nc-bar-warn {
      background: var(--danger);
    }

    .nc-header {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-bottom: 10px;
      padding-left: 12px;
    }

    .nc-currency-tag {
      font-family: 'IBM Plex Mono', monospace;
      font-size: 10px;
      font-weight: 700;
      letter-spacing: .08em;
      padding: 3px 8px;
      border-radius: 6px;
    }

    .nc-tag-info {
      background: var(--info-bg);
      color: var(--info);
    }

    .nc-tag-warn {
      background: var(--danger-bg);
      color: var(--danger);
    }

    .nc-time {
      font-size: 10px;
      color: var(--txt3);
      font-family: 'IBM Plex Mono', monospace;
    }

    .news-card h4 {
      font-size: 14px;
      font-weight: 700;
      color: var(--txt);
      margin-bottom: 8px;
      padding-left: 12px;
      line-height: 1.45;
      font-family: 'DM Sans', sans-serif;
    }

    .news-card p {
      font-size: 12px;
      color: var(--txt2);
      line-height: 1.7;
      padding-left: 12px;
      font-family: 'DM Sans', sans-serif;
    }

    .nc-footer {
      display: flex;
      justify-content: flex-end;
      gap: 6px;
      margin-top: 14px;
      padding-left: 12px;
    }

    /* ══════════════════════════════════════════
           CMS COUNTRY CHIPS
        ══════════════════════════════════════════ */
    .country-chip-grid {
      display: grid;
      grid-template-columns: repeat(5, 1fr);
      gap: 8px;
      margin-bottom: 22px;
    }

    .cc {
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: 5px;
      padding: 10px 8px;
      border-radius: 12px;
      border: 1.5px solid var(--border);
      background: var(--bg-surface);
      cursor: pointer;
      transition: all .15s;
      text-align: center;
    }

    .cc:hover {
      border-color: var(--accent-mid);
      background: var(--accent-light);
    }

    .cc.active {
      border-color: var(--accent);
      background: var(--accent);
      color: white;
    }

    .cc-flag {
      font-size: 22px;
      line-height: 1;
    }

    .cc-code {
      font-family: 'IBM Plex Mono', monospace;
      font-size: 11px;
      font-weight: 700;
    }

    .cc-country {
      font-size: 9px;
      color: var(--txt3);
      font-family: 'DM Sans', sans-serif;
    }

    .cc.active .cc-country {
      color: rgba(255, 255, 255, 0.7);
    }

    .cc.active .cc-code {
      color: white;
    }

    /* ══════════════════════════════════════════
           CMS FORM
        ══════════════════════════════════════════ */
    .sf label {
      display: block;
      font-size: 10px;
      font-weight: 700;
      color: var(--txt3);
      letter-spacing: .08em;
      text-transform: uppercase;
      margin-bottom: 7px;
      font-family: 'DM Sans', sans-serif;
    }

    .sf .fc {
      width: 100%;
      background: var(--bg-page);
      border: 1px solid var(--border);
      border-radius: 10px;
      padding: 10px 13px;
      color: var(--txt);
      font-size: 13px;
      font-family: 'DM Sans', sans-serif;
      outline: none;
      transition: border-color .2s, box-shadow .2s;
    }

    .sf .fc:focus {
      border-color: var(--accent-mid);
      background: var(--bg-surface);
      box-shadow: 0 0 0 3px rgba(79, 70, 229, .07);
    }

    .sf textarea.fc {
      resize: vertical;
      line-height: 1.65;
    }

    /* ══════════════════════════════════════════
           API / CONFIG
        ══════════════════════════════════════════ */
    .api-steps {
      display: flex;
      flex-direction: column;
      gap: 24px;
    }

    .api-step {
      display: flex;
      gap: 16px;
      align-items: flex-start;
    }

    .step-num {
      width: 32px;
      height: 32px;
      border-radius: 50%;
      background: var(--accent);
      color: white;
      font-size: 14px;
      font-weight: 700;
      display: flex;
      align-items: center;
      justify-content: center;
      flex-shrink: 0;
      font-family: 'IBM Plex Mono', monospace;
    }

    .step-body h5 {
      font-size: 14px;
      font-weight: 700;
      color: var(--txt);
      margin-bottom: 6px;
      font-family: 'DM Sans', sans-serif;
    }

    .step-body p {
      font-size: 13px;
      color: var(--txt2);
      line-height: 1.65;
      font-family: 'DM Sans', sans-serif;
    }

    .step-body a {
      color: var(--accent);
      text-decoration: none;
      font-weight: 600;
    }

    .step-body a:hover {
      text-decoration: underline;
    }

    .code-block {
      display: flex;
      align-items: center;
      justify-content: space-between;
      background: var(--bg-page);
      border: 1px solid var(--border);
      border-radius: 10px;
      padding: 11px 16px;
      margin-top: 10px;
      font-family: 'IBM Plex Mono', monospace;
      font-size: 13px;
      color: var(--accent);
      gap: 12px;
      overflow-x: auto;
    }

    .copy-btn {
      background: transparent;
      border: none;
      color: var(--txt3);
      cursor: pointer;
      transition: color .2s;
      font-size: 13px;
      padding: 2px 6px;
      flex-shrink: 0;
    }

    .copy-btn:hover {
      color: var(--accent);
    }

    .info-box {
      background: var(--info-bg);
      border: 1px solid #BAE6FD;
      border-radius: 14px;
      padding: 18px 20px;
    }

    .info-box h5 {
      font-size: 13px;
      font-weight: 700;
      color: var(--info);
      margin-bottom: 12px;
      display: flex;
      align-items: center;
      gap: 7px;
      font-family: 'DM Sans', sans-serif;
    }

    .info-box ul {
      padding-left: 16px;
      display: flex;
      flex-direction: column;
      gap: 8px;
    }

    .info-box li {
      font-size: 12px;
      color: var(--txt2);
      line-height: 1.6;
      font-family: 'DM Sans', sans-serif;
    }

    .info-box code {
      background: rgba(2, 132, 199, .1);
      color: var(--info);
      padding: 1px 5px;
      border-radius: 4px;
      font-family: 'IBM Plex Mono', monospace;
      font-size: 11px;
    }

    /* ══════════════════════════════════════════
           MODALS
        ══════════════════════════════════════════ */
    .modal-overlay {
      display: none;
      position: fixed;
      inset: 0;
      background: rgba(0, 0, 0, .48);
      z-index: 1000;
      align-items: center;
      justify-content: center;
      backdrop-filter: blur(2px);
    }

    .modal-box {
      background: var(--bg-surface);
      border-radius: 18px;
      border: 1px solid var(--border);
      max-width: 95vw;
      box-shadow: 0 24px 60px rgba(0, 0, 0, .2);
    }

    .modal-header {
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 16px 22px;
      border-bottom: 1px solid var(--border);
    }

    .modal-header h3 {
      font-size: 14px;
      font-weight: 700;
      color: var(--txt);
      font-family: 'DM Sans', sans-serif;
    }

    .modal-footer {
      display: flex;
      justify-content: flex-end;
      gap: 8px;
      padding: 14px 22px;
      border-top: 1px solid var(--border);
    }

    .modal-body {
      padding: 20px 22px;
    }

    /* ══════════════════════════════════════════
           RESPONSIVE
        ══════════════════════════════════════════ */
    @media (max-width: 1200px) {
      .stats-row {
        grid-template-columns: repeat(3, 1fr);
      }

      .chart-layout {
        grid-template-columns: 1fr;
      }

      .news-grid {
        grid-template-columns: 1fr;
      }

      .country-chip-grid {
        grid-template-columns: repeat(4, 1fr);
      }
    }

    @media (max-width: 768px) {
      .stats-row {
        grid-template-columns: repeat(2, 1fr);
      }

      .bh-rates {
        display: none;
      }

      .country-chip-grid {
        grid-template-columns: repeat(3, 1fr);
      }
    }
  </style>
@endpush

@section('content')

  {{-- ══════════════════════════════════════════
  PAGE HEADER + BASE SELECTOR
  ══════════════════════════════════════════ --}}
  <div class="page-header-row">
    <div>
      <h2>Quản Lý Tỷ Giá <span style="color:var(--accent);">ASEAN</span></h2>
      <p>Tỷ giá nội khối · 10 quốc gia Đông Nam Á · Quốc gia chủ là trung tâm tham chiếu</p>
    </div>
    <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap;">
      <span style="font-size:12px;color:var(--txt3);font-family:'DM Sans',sans-serif;">Quốc gia chủ:</span>
      <div class="base-pill" onclick="openBaseModal()" id="topbar-selector">
        <span class="bp-flag" id="topbar-flag">🇹🇭</span>
        <div>
          <div class="bp-code" id="topbar-code">THB</div>
          <div class="bp-label" id="topbar-name">Thai Baht</div>
        </div>
        <i class="fas fa-chevron-down"></i>
      </div>
      <form action="{{ route('admin.rates.update-rates') }}" method="POST" style="margin:0;">
        @csrf
        <button type="submit" class="btn-secondary" style="font-size:12px;">
          <i class="fas fa-sync-alt"></i> Cập nhật tỷ giá
        </button>
      </form>
    </div>
  </div>

  {{-- ══════════════════════════════════════════
  BASE CURRENCY HERO BANNER
  ══════════════════════════════════════════ --}}
  <div class="base-hero" id="base-hero">
    <div class="bh-identity">
      <div class="bh-flag-wrap" id="hero-flag">🇹🇭</div>
      <div class="bh-text">
        <div class="bh-eyebrow">Tiền tệ cơ sở · Base Currency</div>
        <div class="bh-code" id="hero-code">THB</div>
        <div class="bh-name" id="hero-name">Thai Baht · Baht Thái Lan</div>
      </div>
    </div>

    {{-- Quick rates vs 4 other ASEAN currencies --}}
    <div class="bh-rates" id="hero-rates">
      <div class="bh-rate-chip">
        <div class="brc-label">1 THB =</div>
        <div class="brc-val">716.4</div>
        <div class="brc-cur">VND</div>
      </div>
      <div class="bh-rate-chip">
        <div class="brc-label">1 THB =</div>
        <div class="brc-val">0.0371</div>
        <div class="brc-cur">SGD</div>
      </div>
      <div class="bh-rate-chip">
        <div class="brc-label">1 THB =</div>
        <div class="brc-val">0.1183</div>
        <div class="brc-cur">MYR</div>
      </div>
      <div class="bh-rate-chip">
        <div class="brc-label">1 THB =</div>
        <div class="brc-val">437.5</div>
        <div class="brc-cur">IDR</div>
      </div>
    </div>

    <div class="bh-actions">
      <button class="btn-switch-base" onclick="openBaseModal()">
        <i class="fas fa-exchange-alt"></i> Đổi Base
      </button>
      <span style="font-size:10px;color:rgba(255,255,255,0.45);text-align:center;font-family:'DM Sans',sans-serif;">
        Cập nhật {{ now()->format('H:i') }} ICT
      </span>
    </div>
  </div>

  {{-- ══════════════════════════════════════════
  STAT CARDS
  ══════════════════════════════════════════ --}}
  <div class="stats-row">

    <div class="stat-card sc-green">
      <div class="sc-label">
        Mạnh nhất vs Base
        <span class="badge badge-success" style="font-size:9px;">BEST</span>
      </div>
      <div class="sc-value">{{ $mostStable->currency_code ?? 'SGD' }}</div>
      <div class="sc-sub {{ ($mostStable->change_percentage ?? 0) >= 0 ? 'up' : 'down' }}">
        <i class="fas fa-caret-{{ ($mostStable->change_percentage ?? 0) >= 0 ? 'up' : 'down' }}"></i>
        {{ abs($mostStable->change_percentage ?? 0.32) }}% vs base hôm nay
      </div>
    </div>

    <div class="stat-card sc-red">
      <div class="sc-label">
        Biến động lớn nhất
        <span class="badge badge-danger" style="font-size:9px;">HOT</span>
      </div>
      <div class="sc-value">{{ $mostVolatile->currency_code ?? 'MYR' }}</div>
      <div class="sc-sub {{ ($mostVolatile->change_percentage ?? 0) >= 0 ? 'up' : 'down' }}">
        <i class="fas fa-caret-{{ ($mostVolatile->change_percentage ?? 0) >= 0 ? 'up' : 'down' }}"></i>
        {{ abs($mostVolatile->change_percentage ?? 1.84) }}% trong 24h
      </div>
    </div>

    <div class="stat-card sc-blue">
      <div class="sc-label">Tiền tệ ASEAN</div>
      <div class="sc-value">{{ $totalCurrencies ?? 10 }}</div>
      <div class="sc-sub">
        <i class="fas fa-globe-asia" style="color:var(--accent);"></i>
        {{ ($totalCurrencies ?? 10) - 1 }} cặp tỷ giá hoạt động
      </div>
    </div>

    <div class="stat-card sc-amber">
      <div class="sc-label">Lượt tra cứu hôm nay</div>
      <div class="sc-value">18,420</div>
      <div class="sc-sub up">
        <i class="fas fa-caret-up"></i> +12% so với hôm qua
      </div>
    </div>

    <div class="stat-card sc-gray">
      <div class="sc-label">Cập nhật lần cuối</div>
      <div style="font-family:'IBM Plex Mono',monospace;font-size:20px;font-weight:700;color:var(--txt);line-height:1.3;">
        {{ now()->format('H:i:s') }}<br>
        <span style="font-size:12px;color:var(--txt3);font-weight:400;">{{ now()->format('d/m/Y') }}</span>
      </div>
    </div>

  </div>

  {{-- ══════════════════════════════════════════
  TAB RAIL
  ══════════════════════════════════════════ --}}
  <div class="tab-rail">
    <button class="trb active" onclick="showTab('tab-rates', this)">
      <i class="fas fa-table"></i> Tỷ giá nội khối
    </button>
    <button class="trb" onclick="showTab('tab-matrix', this)">
      <i class="fas fa-th"></i> Bảng chéo ASEAN
    </button>
    <button class="trb" onclick="showTab('tab-charts', this)">
      <i class="fas fa-chart-area"></i> Biểu đồ
    </button>
    <button class="trb" onclick="showTab('tab-ai', this)">
      <i class="fas fa-robot"></i> AI Analysis
    </button>
    <button class="trb" onclick="showTab('tab-cms', this)">
      <i class="fas fa-sliders-h"></i> CMS
    </button>
    <button class="trb" onclick="showTab('tab-api', this)">
      <i class="fas fa-key"></i> API Config
    </button>
  </div>

  {{-- ══════════════════════════════════════════
  TAB 1: TỶ GIÁ NỘI KHỐI
  Logic: Base → 9 nước ASEAN còn lại, 2 chiều
  ══════════════════════════════════════════ --}}
  <div id="tab-rates" class="tab-content active">

    <div class="alert-bar alert-info">
      <i class="fas fa-info-circle"></i>
      <span>
        Đang hiển thị <strong id="alert-base-text">THB (Thai Baht)</strong> so với 9 đồng tiền ASEAN còn lại.
        Cột <strong>"1 [Base] ="</strong> cho thấy bạn nhận được bao nhiêu khi đổi 1 đơn vị base.
        Cột <strong>"1 [Target] ="</strong> là chiều ngược lại. Nhấn <strong>Đổi Base</strong> để chuyển góc nhìn.
      </span>
    </div>

    <div class="panel">
      <div class="panel-head">
        <div style="display:flex;gap:10px;align-items:center;flex-wrap:wrap;">
          <div class="header-search" style="width:260px;">
            <i class="fas fa-search"></i>
            <input type="text" id="rateSearch" placeholder="Tìm mã tiền tệ, quốc gia..."
              onkeyup="filterRates(this.value)">
          </div>
          <select class="form-control" style="width:auto;">
            <option value="">Tất cả ASEAN</option>
            <option value="mainland">Đông Nam Á Lục địa</option>
            <option value="island">Đảo + Bán đảo</option>
          </select>
        </div>
        <div style="display:flex;gap:8px;">
          <button class="btn-secondary" style="font-size:12px;">
            <i class="fas fa-download"></i> Xuất CSV
          </button>
          <button class="btn-glow" onclick="document.getElementById('addRateModal').style.display='flex'">
            <i class="fas fa-plus"></i> Thêm tiền tệ
          </button>
        </div>
      </div>

      <div style="overflow-x:auto;">
        <table class="premium-table" id="rates-table">
          <thead>
            <tr>
              <th>Quốc gia</th>
              <th>Mã · Tên tiền tệ</th>
              {{-- Header cột 3 & 4 cập nhật động theo base --}}
              <th id="th-base-to-target">1 THB =</th>
              <th id="th-target-to-base">1 [Target] = THB</th>
              <th>24h</th>
              <th>7 ngày</th>
              <th>Trạng thái</th>
              <th style="text-align:right;">Thao tác</th>
            </tr>
          </thead>
          <tbody id="rates-tbody">
            {{-- ─────────────────────────────────────────
            Laravel renders initial rows (Base = THB)
            JS rebuild kicks in when user switches Base
            ───────────────────────────────────────── --}}
            @foreach($rates as $rate)
              @if($rate->currency_code !== 'THB')
                <tr data-code="{{ $rate->currency_code }}" data-country="{{ $rate->country }}">
                  {{-- Quốc gia --}}
                  <td>
                    <div style="display:flex;align-items:center;gap:11px;">
                      <img class="flag-img" src="https://flagcdn.com/w80/{{ strtolower($rate->flag_icon ?? 'vn') }}.png"
                        alt="{{ $rate->country }}" onerror="this.style.display='none'">
                      <span
                        style="font-weight:600;font-size:13px;font-family:'DM Sans',sans-serif;">{{ $rate->country }}</span>
                    </div>
                  </td>
                  {{-- Mã / Tên --}}
                  <td>
                    <div class="cur-code">{{ $rate->currency_code }}</div>
                    <div class="cur-name">{{ $rate->currency_name }}</div>
                  </td>
                  {{-- 1 BASE → TARGET (chiều đi) --}}
                  <td>
                    <div class="rate-primary">
                      {{ number_format($rate->rate_from_base, 4) }} {{ $rate->currency_code }}
                    </div>
                    <div class="rate-inverse">1 THB → {{ $rate->currency_code }}</div>
                  </td>
                  {{-- 1 TARGET → BASE (chiều về) --}}
                  <td>
                    <div class="rate-primary">
                      {{ number_format(1 / $rate->rate_from_base, 6) }} THB
                    </div>
                    <div class="rate-inverse">1 {{ $rate->currency_code }} → THB</div>
                  </td>
                  {{-- 24h --}}
                  <td>
                    <span class="chg-pill {{ $rate->change_percentage >= 0 ? 'chg-up' : 'chg-down' }}">
                      <i class="fas fa-arrow-{{ $rate->change_percentage >= 0 ? 'up' : 'down' }}"></i>
                      {{ abs($rate->change_percentage) }}%
                    </span>
                  </td>
                  {{-- 7d --}}
                  <td>
                    <span class="chg-pill {{ ($rate->change_7d ?? 0) >= 0 ? 'chg-up' : 'chg-down' }}">
                      <i class="fas fa-arrow-{{ ($rate->change_7d ?? 0) >= 0 ? 'up' : 'down' }}"></i>
                      {{ abs($rate->change_7d ?? 0) }}%
                    </span>
                  </td>
                  {{-- Toggle --}}
                  <td>
                    <label class="toggle">
                      <input type="checkbox" {{ $rate->status ? 'checked' : '' }}
                        onchange="toggleRateStatus({{ $rate->id }})">
                      <span class="toggle-track"></span>
                    </label>
                  </td>
                  {{-- Actions --}}
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
              @endif
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>

  {{-- ══════════════════════════════════════════
  TAB 2: BẢNG CHÉO ASEAN (Cross-rate Matrix)
  Logic: Hàng = nguồn, Cột = đích, không qua USD
  ══════════════════════════════════════════ --}}
  <div id="tab-matrix" class="tab-content">

    <div class="alert-bar alert-warn">
      <i class="fas fa-exclamation-triangle"></i>
      <span>
        Bảng chéo hiển thị tỷ giá trực tiếp giữa <strong>tất cả 10 đồng tiền ASEAN</strong> — không qua USD làm trung
        gian.
        <strong>Hàng</strong> = đồng tiền nguồn · <strong>Cột</strong> = đồng tiền đích.
        Ô màu xanh = base currency đang chọn (<span id="matrix-base-label">THB</span>).
      </span>
    </div>

    <div class="panel">
      <div class="panel-head">
        <h3>Cross Rate Matrix · ASEAN 10 × 10</h3>
        <div style="display:flex;gap:8px;align-items:center;">
          <span style="font-size:11px;color:var(--txt3);font-family:'IBM Plex Mono',monospace;">
            {{ now()->format('H:i') }} ICT
          </span>
          <button class="btn-secondary" style="font-size:12px;">
            <i class="fas fa-download"></i> Tải bảng
          </button>
        </div>
      </div>
      <div class="matrix-wrap">
        <table class="matrix-tbl" id="matrix-table">
          {{-- Rendered by JS --}}
        </table>
      </div>
    </div>
  </div>

  {{-- ══════════════════════════════════════════
  TAB 3: BIỂU ĐỒ
  Logic: Cặp nội khối ASEAN, không phải Base/USD
  ══════════════════════════════════════════ --}}
  <div id="tab-charts" class="tab-content">
    <div class="chart-layout">

      {{-- Sidebar --}}
      <div class="panel" style="padding:18px;">
        <div class="sidebar-label">Cặp tiền tệ ASEAN</div>
        <select class="form-control" style="width:100%;margin-bottom:14px;font-family:'IBM Plex Mono',monospace;"
          id="chart-pair">
          {{-- Options built dynamically by JS based on current base --}}
        </select>

        <div class="divider"></div>

        <div class="sidebar-label">Kiểu biểu đồ</div>
        <div class="chart-sidebar">
          <button class="chart-type-btn active" data-type="area"><i class="fas fa-chart-area"></i> Area</button>
          <button class="chart-type-btn" data-type="line"><i class="fas fa-chart-line"></i> Line</button>
          <button class="chart-type-btn" data-type="bar"><i class="fas fa-chart-bar"></i> Bar</button>
          <button class="chart-type-btn" data-type="candlestick"><i class="fas fa-chart-candlestick"></i>
            Candlestick</button>
        </div>

        <div class="divider"></div>

        <button class="btn-glow" style="width:100%;justify-content:center;" onclick="renderChart()">
          <i class="fas fa-check"></i> Áp dụng
        </button>
      </div>

      {{-- Chart panel --}}
      <div class="panel">
        <div class="panel-head">
          <h3 id="chart-title">THB / VND · Biểu đồ tỷ giá nội khối</h3>
          <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap;">
            <span class="chg-pill chg-up" id="chart-change-pill">
              <i class="fas fa-arrow-up"></i> +0.47% (7N)
            </span>
            <div class="time-pills">
              <button class="time-pill active" onclick="setTimePeriod(this,'1N')">1N</button>
              <button class="time-pill" onclick="setTimePeriod(this,'1T')">1T</button>
              <button class="time-pill" onclick="setTimePeriod(this,'1TH')">1TH</button>
              <button class="time-pill" onclick="setTimePeriod(this,'3TH')">3TH</button>
              <button class="time-pill" onclick="setTimePeriod(this,'1NM')">1NM</button>
            </div>
          </div>
        </div>
        <div style="padding:20px;">
          <div id="chartPreview" style="height:320px;"></div>
        </div>
      </div>

    </div>
  </div>

  {{-- ══════════════════════════════════════════
  TAB 4: AI ANALYSIS
  Logic: Bản tin gắn tag mã tiền tệ ASEAN cụ thể
  ══════════════════════════════════════════ --}}
  <div id="tab-ai" class="tab-content">
    <div
      style="display:flex;justify-content:space-between;align-items:center;margin-bottom:18px;flex-wrap:wrap;gap:10px;">
      <div>
        <h3 style="font-size:18px;font-weight:800;color:var(--txt);font-family:'Syne',sans-serif;">
          Bản tin AI · <span style="color:var(--accent);">ASEAN FX</span>
        </h3>
        <p style="font-size:12px;color:var(--txt3);margin-top:4px;font-family:'DM Sans',sans-serif;">
          Phân tích tự động bởi Gemini AI theo ngữ cảnh tiền tệ ASEAN — gắn tag theo từng đồng tiền nội khối
        </p>
      </div>
      <div style="display:flex;gap:8px;flex-wrap:wrap;">
        <form action="{{ route('admin.rates.update-ai') }}" method="POST" style="margin:0;">
          @csrf
          <button type="submit" class="btn-glow">
            <i class="fas fa-robot"></i> Tạo phân tích AI
          </button>
        </form>
        <button class="btn-secondary" onclick="document.getElementById('addNewsModal').style.display='flex'">
          <i class="fas fa-pen"></i> Soạn thủ công
        </button>
      </div>
    </div>

    <div class="news-grid">
      @foreach($news as $item)
        <div class="news-card">
          <div class="nc-accent-bar {{ $item->type == 'warning' ? 'nc-bar-warn' : 'nc-bar-info' }}"></div>
          <div class="nc-header">
            {{-- Tag gắn mã tiền tệ ASEAN cụ thể thay vì generic --}}
            <span class="nc-currency-tag {{ $item->type == 'warning' ? 'nc-tag-warn' : 'nc-tag-info' }}">
              {{ strtoupper($item->currency_tag ?? 'ASEAN') }} · {{ strtoupper($item->type ?? 'ANALYSIS') }}
            </span>
            <span class="nc-time">{{ $item->created_at->diffForHumans() }}</span>
          </div>
          <h4>{{ $item->title }}</h4>
          <p>{{ Str::limit($item->content, 200) }}</p>
          <div class="nc-footer">
            <button class="btn-icon-glass" title="Chỉnh sửa"><i class="fas fa-pen"></i></button>
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

  {{-- ══════════════════════════════════════════
  TAB 5: CMS
  Logic: Cấu hình riêng theo từng quốc gia ASEAN
  ══════════════════════════════════════════ --}}
  <div id="tab-cms" class="tab-content">
    <div
      style="display:flex;justify-content:space-between;align-items:center;margin-bottom:18px;flex-wrap:wrap;gap:12px;">
      <div>
        <h3 style="font-size:18px;font-weight:800;color:var(--txt);font-family:'Syne',sans-serif;">Cấu hình CMS · Theo
          Quốc Gia</h3>
        <p style="font-size:12px;color:var(--txt3);margin-top:4px;font-family:'DM Sans',sans-serif;">
          Mỗi quốc gia ASEAN có trang tỷ giá riêng với hero title, mô tả SEO và CTA độc lập
        </p>
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

        <div
          style="font-size:10px;font-weight:700;color:var(--txt3);text-transform:uppercase;letter-spacing:.07em;margin-bottom:12px;font-family:'DM Sans',sans-serif;">
          <i class="fas fa-globe-asia" style="color:var(--accent);margin-right:6px;"></i>
          Chọn quốc gia cần cấu hình
        </div>

        <div class="country-chip-grid" id="cms-country-grid">
          <div class="cc active" onclick="selectCmsCountry(this,'THB','Thái Lan')">
            <span class="cc-flag">🇹🇭</span>
            <span class="cc-code">THB</span>
            <span class="cc-country">Thái Lan</span>
          </div>
          <div class="cc" onclick="selectCmsCountry(this,'VND','Việt Nam')">
            <span class="cc-flag">🇻🇳</span>
            <span class="cc-code">VND</span>
            <span class="cc-country">Việt Nam</span>
          </div>
          <div class="cc" onclick="selectCmsCountry(this,'SGD','Singapore')">
            <span class="cc-flag">🇸🇬</span>
            <span class="cc-code">SGD</span>
            <span class="cc-country">Singapore</span>
          </div>
          <div class="cc" onclick="selectCmsCountry(this,'MYR','Malaysia')">
            <span class="cc-flag">🇲🇾</span>
            <span class="cc-code">MYR</span>
            <span class="cc-country">Malaysia</span>
          </div>
          <div class="cc" onclick="selectCmsCountry(this,'IDR','Indonesia')">
            <span class="cc-flag">🇮🇩</span>
            <span class="cc-code">IDR</span>
            <span class="cc-country">Indonesia</span>
          </div>
          <div class="cc" onclick="selectCmsCountry(this,'PHP','Philippines')">
            <span class="cc-flag">🇵🇭</span>
            <span class="cc-code">PHP</span>
            <span class="cc-country">Philippines</span>
          </div>
          <div class="cc" onclick="selectCmsCountry(this,'MMK','Myanmar')">
            <span class="cc-flag">🇲🇲</span>
            <span class="cc-code">MMK</span>
            <span class="cc-country">Myanmar</span>
          </div>
          <div class="cc" onclick="selectCmsCountry(this,'KHR','Campuchia')">
            <span class="cc-flag">🇰🇭</span>
            <span class="cc-code">KHR</span>
            <span class="cc-country">Campuchia</span>
          </div>
          <div class="cc" onclick="selectCmsCountry(this,'LAK','Lào')">
            <span class="cc-flag">🇱🇦</span>
            <span class="cc-code">LAK</span>
            <span class="cc-country">Lào</span>
          </div>
          <div class="cc" onclick="selectCmsCountry(this,'BND','Brunei')">
            <span class="cc-flag">🇧🇳</span>
            <span class="cc-code">BND</span>
            <span class="cc-country">Brunei</span>
          </div>
        </div>

        {{-- ── CMS form: fields apply to selected country ── --}}
        <form action="{{ route('admin.rates.settings') }}" method="POST" enctype="multipart/form-data" class="sf">
          @csrf
          <input type="hidden" name="country_code" id="cms-country-input" value="THB">

          <div style="display:grid;grid-template-columns:1fr 1fr;gap:18px;">
            <div>
              <label>Tiêu đề hero (trang <span id="cms-label-code">THB</span>)</label>
              <input type="text" name="hero_title" class="fc" value="{{ $settings->hero_title }}"
                placeholder="VD: Tỷ giá Baht Thái hôm nay với 9 nước ASEAN">
            </div>
            <div>
              <label>Tiền tệ so sánh mặc định (trên trang frontend)</label>
              <select name="default_currency" class="fc">
                <option value="VND" {{ ($settings->default_currency ?? '') == 'VND' ? 'selected' : '' }}>VND — Việt Nam Đồng
                </option>
                <option value="SGD" {{ ($settings->default_currency ?? '') == 'SGD' ? 'selected' : '' }}>SGD — Singapore
                  Dollar</option>
                <option value="MYR" {{ ($settings->default_currency ?? '') == 'MYR' ? 'selected' : '' }}>MYR — Malaysian
                  Ringgit</option>
                <option value="USD" {{ ($settings->default_currency ?? '') == 'USD' ? 'selected' : '' }}>USD — US Dollar
                </option>
              </select>
            </div>
            <div style="grid-column:1/-1;">
              <label>Mô tả trang (SEO · Hero sub-title)</label>
              <textarea name="hero_description" class="fc" rows="3">{{ $settings->hero_description }}</textarea>
            </div>
            <div>
              <label>Text nút CTA</label>
              <input type="text" name="cta_text" class="fc" value="{{ $settings->cta_text }}"
                placeholder="VD: Xem tỷ giá THB hôm nay">
            </div>
            <div>
              <label>Hình nền Banner</label>
              <input type="file" name="banner_image" class="fc" style="padding:8px 12px;cursor:pointer;">
            </div>
          </div>

          <div style="margin-top:18px;">
            <button type="submit" class="btn-glow">
              <i class="fas fa-save"></i> Lưu cấu hình cho quốc gia này
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>

  {{-- ══════════════════════════════════════════
  TAB 6: API CONFIG
  ══════════════════════════════════════════ --}}
  <div id="tab-api" class="tab-content">
    <div style="display:grid;grid-template-columns:1fr 320px;gap:18px;align-items:start;">

      <div class="panel">
        <div class="panel-head">
          <h3>Hướng dẫn cấu hình API</h3>
          <span class="badge badge-success">
            <i class="fas fa-circle" style="font-size:7px;"></i> Kết nối
          </span>
        </div>
        <div class="panel-body">
          <div class="api-steps">

            <div class="api-step">
              <div class="step-num">1</div>
              <div class="step-body">
                <h5>Lấy API Key Gemini</h5>
                <p>Truy cập <a href="https://aistudio.google.com/app/apikey" target="_blank">Google AI Studio</a>
                  để tạo API Key miễn phí. Dùng cho tính năng AI Analysis ASEAN FX.</p>
              </div>
            </div>

            <div class="api-step">
              <div class="step-num">2</div>
              <div class="step-body">
                <h5>Cấu hình file .env</h5>
                <p>Mở file <code
                    style="background:var(--accent-light);color:var(--accent);padding:1px 6px;border-radius:4px;font-family:'IBM Plex Mono',monospace;font-size:12px;">.env</code>
                  ở thư mục gốc và thêm:</p>
                <div class="code-block">
                  <span>GEMINI_API_KEY=your_api_key_here</span>
                  <button class="copy-btn" onclick="copyCode('GEMINI_API_KEY=your_api_key_here', this)">
                    <i class="far fa-copy"></i>
                  </button>
                </div>
                <p style="margin-top:10px;">Nguồn tỷ giá ASEAN (không qua USD):</p>
                <div class="code-block">
                  <span>EXCHANGE_API=https://api.exchangerate-api.com/v4</span>
                  <button class="copy-btn" onclick="copyCode('EXCHANGE_API=https://api.exchangerate-api.com/v4', this)">
                    <i class="far fa-copy"></i>
                  </button>
                </div>
                <p style="margin-top:10px;">Base currency mặc định cho hệ thống:</p>
                <div class="code-block">
                  <span>ASEAN_BASE_CURRENCY=THB</span>
                  <button class="copy-btn" onclick="copyCode('ASEAN_BASE_CURRENCY=THB', this)">
                    <i class="far fa-copy"></i>
                  </button>
                </div>
              </div>
            </div>

            <div class="api-step">
              <div class="step-num">3</div>
              <div class="step-body">
                <h5>Kiểm tra pipeline nội khối</h5>
                <p>Sau khi lưu, vào tab <strong style="color:var(--accent);">AI Analysis</strong> và nhấn
                  <strong style="color:var(--accent);">Tạo phân tích</strong>.
                  Hệ thống sẽ tính cross-rate trực tiếp giữa các đồng ASEAN — không routing qua USD.
                </p>
              </div>
            </div>

          </div>
        </div>
      </div>

      <div class="info-box">
        <h5><i class="fas fa-shield-alt"></i> Bảo mật & Lưu ý</h5>
        <ul>
          <li>Không chia sẻ file <code>.env</code> hoặc commit lên Git công khai.</li>
          <li>API Key Gemini miễn phí nhưng giới hạn lượt gọi mỗi phút.</li>
          <li>Nếu API lỗi, hệ thống tự chuyển sang chế độ Demo.</li>
          <li>Nên rotate API Key định kỳ 3–6 tháng/lần.</li>
          <li>Nguồn tỷ giá nội khối chính xác nhất: <strong>Bank of Thailand</strong>, <strong>SBV</strong>, hoặc
            <strong>MAS</strong>.
          </li>
          <li>Cross-rate matrix tính từ các tỷ giá song phương — không quy đổi qua USD làm mốc trung gian.</li>
        </ul>
      </div>

    </div>
  </div>

  {{-- ══════════════════════════════════════════
  MODAL: THÊM TIỀN TỆ
  ══════════════════════════════════════════ --}}
  <div id="addRateModal" class="modal-overlay" onclick="if(event.target===this)this.style.display='none'">
    <div class="modal-box" style="width:520px;">
      <div class="modal-header">
        <h3>Thêm tiền tệ ASEAN</h3>
        <button class="btn-icon-glass" onclick="document.getElementById('addRateModal').style.display='none'">
          <i class="fas fa-times"></i>
        </button>
      </div>
      <form action="{{ route('admin.rates.store') }}" method="POST">
        @csrf
        <div class="modal-body">
          <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;" class="sf">
            <div>
              <label>Mã tiền tệ</label>
              <input type="text" name="currency_code" class="fc" placeholder="VD: KHR" maxlength="3" required>
            </div>
            <div>
              <label>Tên tiền tệ</label>
              <input type="text" name="currency_name" class="fc" placeholder="VD: Cambodian Riel" required>
            </div>
            <div>
              <label>Quốc gia</label>
              <input type="text" name="country" class="fc" placeholder="VD: Campuchia" required>
            </div>
            <div>
              <label>Mã cờ ISO (2 ký tự)</label>
              <input type="text" name="flag_icon" class="fc" placeholder="VD: kh" maxlength="2" required>
            </div>
            <div>
              <label>Tỷ giá so với base hiện tại (<span id="modal-base-code">THB</span>)</label>
              <input type="number" name="rate_from_base" class="fc" placeholder="0.000000" step="0.000001" required>
            </div>
            <div>
              <label>Trạng thái</label>
              <select name="status" class="fc">
                <option value="1">Hoạt động</option>
                <option value="0">Tắt</option>
              </select>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn-secondary" style="font-size:12px;"
            onclick="document.getElementById('addRateModal').style.display='none'">Huỷ</button>
          <button type="submit" class="btn-glow"><i class="fas fa-save"></i> Lưu tiền tệ</button>
        </div>
      </form>
    </div>
  </div>

  {{-- ══════════════════════════════════════════
  MODAL: ĐỔI BASE CURRENCY
  ══════════════════════════════════════════ --}}
  <div id="baseModal" class="modal-overlay" onclick="if(event.target===this)this.style.display='none'">
    <div class="modal-box" style="width:480px;">
      <div class="modal-header">
        <h3>Chọn quốc gia chủ (Base Currency)</h3>
        <button class="btn-icon-glass" onclick="document.getElementById('baseModal').style.display='none'">
          <i class="fas fa-times"></i>
        </button>
      </div>
      <div class="modal-body">
        <p style="font-size:12px;color:var(--txt2);margin-bottom:16px;line-height:1.7;font-family:'DM Sans',sans-serif;">
          Khi chọn quốc gia làm <strong>Base</strong>, toàn bộ bảng tỷ giá chuyển sang góc nhìn của đồng tiền đó —
          hiển thị tỷ giá <strong>1 [Base] → 9 đồng tiền ASEAN còn lại</strong> và chiều ngược lại.
        </p>
        <div class="country-chip-grid" id="base-modal-grid">
          <div class="cc active" onclick="setBase(this,'th','🇹🇭','THB','Thai Baht','Baht Thái Lan')">
            <span class="cc-flag">🇹🇭</span><span class="cc-code">THB</span><span class="cc-country">Thái Lan</span>
          </div>
          <div class="cc" onclick="setBase(this,'vn','🇻🇳','VND','Việt Nam Đồng','Đồng Việt Nam')">
            <span class="cc-flag">🇻🇳</span><span class="cc-code">VND</span><span class="cc-country">Việt Nam</span>
          </div>
          <div class="cc" onclick="setBase(this,'sg','🇸🇬','SGD','Singapore Dollar','Đô la Singapore')">
            <span class="cc-flag">🇸🇬</span><span class="cc-code">SGD</span><span class="cc-country">Singapore</span>
          </div>
          <div class="cc" onclick="setBase(this,'my','🇲🇾','MYR','Malaysian Ringgit','Ringgit Malaysia')">
            <span class="cc-flag">🇲🇾</span><span class="cc-code">MYR</span><span class="cc-country">Malaysia</span>
          </div>
          <div class="cc" onclick="setBase(this,'id','🇮🇩','IDR','Indonesian Rupiah','Rupiah Indonesia')">
            <span class="cc-flag">🇮🇩</span><span class="cc-code">IDR</span><span class="cc-country">Indonesia</span>
          </div>
          <div class="cc" onclick="setBase(this,'ph','🇵🇭','PHP','Philippine Peso','Peso Philippines')">
            <span class="cc-flag">🇵🇭</span><span class="cc-code">PHP</span><span class="cc-country">Philippines</span>
          </div>
          <div class="cc" onclick="setBase(this,'mm','🇲🇲','MMK','Myanmar Kyat','Kyat Myanmar')">
            <span class="cc-flag">🇲🇲</span><span class="cc-code">MMK</span><span class="cc-country">Myanmar</span>
          </div>
          <div class="cc" onclick="setBase(this,'kh','🇰🇭','KHR','Cambodian Riel','Riel Campuchia')">
            <span class="cc-flag">🇰🇭</span><span class="cc-code">KHR</span><span class="cc-country">Campuchia</span>
          </div>
          <div class="cc" onclick="setBase(this,'la','🇱🇦','LAK','Lao Kip','Kip Lào')">
            <span class="cc-flag">🇱🇦</span><span class="cc-code">LAK</span><span class="cc-country">Lào</span>
          </div>
          <div class="cc" onclick="setBase(this,'bn','🇧🇳','BND','Brunei Dollar','Đô la Brunei')">
            <span class="cc-flag">🇧🇳</span><span class="cc-code">BND</span><span class="cc-country">Brunei</span>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button class="btn-secondary" style="font-size:12px;"
          onclick="document.getElementById('baseModal').style.display='none'">Huỷ</button>
        <button class="btn-glow" onclick="document.getElementById('baseModal').style.display='none'">
          <i class="fas fa-check"></i> Xác nhận
        </button>
      </div>
    </div>
  </div>

@endsection

@push('scripts')
  <script src="https://cdnjs.cloudflare.com/ajax/libs/apexcharts/3.45.2/apexcharts.min.js"></script>
  <script>
        /* ════════════════════════════════════════════════════
           ASEAN CROSS-RATE DATA
           In production: replace with @json($aseanRates) from backend
           All rates are DIRECT bilateral pairs — no USD pivot
        ════════════════════════════════════════════════════ */
    const ASEAN = [
      {
        code: 'THB', name: 'Thai Baht', nameVi: 'Baht Thái Lan', country: 'Thái Lan', flag: 'th', emoji: '🇹🇭',
        rates: { VND: 716.4, SGD: 0.0371, MYR: 0.1183, IDR: 437.5, PHP: 1.568, MMK: 56.84, KHR: 109.6, LAK: 229.4, BND: 0.0363 }
      },
      {
        code: 'VND', name: 'Việt Nam Đồng', nameVi: 'Đồng Việt Nam', country: 'Việt Nam', flag: 'vn', emoji: '🇻🇳',
        rates: { THB: 0.001396, SGD: 0.0000518, MYR: 0.0001652, IDR: 0.6111, PHP: 0.00219, MMK: 0.0794, KHR: 0.153, LAK: 0.3203, BND: 0.0000507 }
      },
      {
        code: 'SGD', name: 'Singapore Dollar', nameVi: 'Đô la Singapore', country: 'Singapore', flag: 'sg', emoji: '🇸🇬',
        rates: { THB: 26.95, VND: 19310, MYR: 3.187, IDR: 11790, PHP: 42.26, MMK: 1531, KHR: 2953, LAK: 6178, BND: 0.9803 }
      },
      {
        code: 'MYR', name: 'Malaysian Ringgit', nameVi: 'Ringgit Malaysia', country: 'Malaysia', flag: 'my', emoji: '🇲🇾',
        rates: { THB: 8.453, VND: 6055, SGD: 0.3138, IDR: 3700, PHP: 13.25, MMK: 480.3, KHR: 926.4, LAK: 1939, BND: 0.3076 }
      },
      {
        code: 'IDR', name: 'Indonesian Rupiah', nameVi: 'Rupiah Indonesia', country: 'Indonesia', flag: 'id', emoji: '🇮🇩',
        rates: { THB: 0.002286, VND: 1.638, SGD: 0.0000848, MYR: 0.0002703, PHP: 0.003583, MMK: 0.1299, KHR: 0.2504, LAK: 0.5242, BND: 0.0000831 }
      },
      {
        code: 'PHP', name: 'Philippine Peso', nameVi: 'Peso Philippines', country: 'Philippines', flag: 'ph', emoji: '🇵🇭',
        rates: { THB: 0.6378, VND: 456.9, SGD: 0.02367, MYR: 0.07547, IDR: 279.2, MMK: 36.24, KHR: 69.89, LAK: 146.3, BND: 0.02317 }
      },
      {
        code: 'MMK', name: 'Myanmar Kyat', nameVi: 'Kyat Myanmar', country: 'Myanmar', flag: 'mm', emoji: '🇲🇲',
        rates: { THB: 0.01759, VND: 12.60, SGD: 0.000653, MYR: 0.002082, IDR: 7.70, PHP: 0.02759, KHR: 1.929, LAK: 4.036, BND: 0.000639 }
      },
      {
        code: 'KHR', name: 'Cambodian Riel', nameVi: 'Riel Campuchia', country: 'Campuchia', flag: 'kh', emoji: '🇰🇭',
        rates: { THB: 0.009124, VND: 6.533, SGD: 0.0003386, MYR: 0.001080, IDR: 3.994, PHP: 0.01431, MMK: 0.5184, LAK: 2.092, BND: 0.0003314 }
      },
      {
        code: 'LAK', name: 'Lao Kip', nameVi: 'Kip Lào', country: 'Lào', flag: 'la', emoji: '🇱🇦',
        rates: { THB: 0.004360, VND: 3.123, SGD: 0.0001619, MYR: 0.0005161, IDR: 1.909, PHP: 0.006836, MMK: 0.2478, KHR: 0.4780, BND: 0.0001584 }
      },
      {
        code: 'BND', name: 'Brunei Dollar', nameVi: 'Đô la Brunei', country: 'Brunei', flag: 'bn', emoji: '🇧🇳',
        rates: { THB: 27.52, VND: 19710, SGD: 1.020, MYR: 3.252, IDR: 12030, PHP: 43.16, MMK: 1563, KHR: 3014, LAK: 6307 }
      },
    ];

    /* sample 24h / 7d changes per currency */
    const CHG24 = { THB: 0, VND: 0.18, SGD: 0.32, MYR: -1.84, IDR: -0.76, PHP: 0.55, MMK: -2.10, KHR: 0.08, LAK: -0.42, BND: 0.29 };
    const CHG7D = { THB: 0, VND: 0.55, SGD: 1.12, MYR: -3.21, IDR: -1.44, PHP: 1.78, MMK: -5.32, KHR: 0.24, LAK: -1.18, BND: 0.91 };

    let currentBase = 'THB';
    let chartInstance = null;
    let currentPeriod = '1N';

    /* ── helper: format rate nicely ── */
    function fmtRate(val) {
      if (val === undefined || val === null) return '?';
      if (val >= 10000) return val.toLocaleString('vi-VN', { maximumFractionDigits: 0 });
      if (val >= 1000) return val.toLocaleString('vi-VN', { maximumFractionDigits: 1 });
      if (val >= 1) return val.toFixed(4);
      if (val >= 0.001) return val.toFixed(4);
      return val.toFixed(6);
    }

    /* ════════════════════════════════════════════════════
       TAB SWITCHING
    ════════════════════════════════════════════════════ */
    function showTab(id, btn) {
      document.querySelectorAll('.tab-content').forEach(t => t.classList.remove('active'));
      document.querySelectorAll('.trb').forEach(b => b.classList.remove('active'));
      document.getElementById(id).classList.add('active');
      btn.classList.add('active');
      if (id === 'tab-charts') setTimeout(renderChart, 80);
      if (id === 'tab-matrix') renderMatrix();
    }

    /* ════════════════════════════════════════════════════
       BASE CURRENCY MODAL
    ════════════════════════════════════════════════════ */
    function openBaseModal() {
      document.getElementById('baseModal').style.display = 'flex';
    }

    /* ════════════════════════════════════════════════════
       SET BASE — the core logic change
       All UI updates cascade from here
    ════════════════════════════════════════════════════ */
    function setBase(el, isoFlag, emoji, code, nameEn, nameVi) {
      /* deselect all chips in modal */
      document.querySelectorAll('#base-modal-grid .cc').forEach(c => c.classList.remove('active'));
      el.classList.add('active');

      currentBase = code;

      /* ── 1. Topbar pill ── */
      document.getElementById('topbar-flag').textContent = emoji;
      document.getElementById('topbar-code').textContent = code;
      document.getElementById('topbar-name').textContent = nameEn;

      /* ── 2. Hero banner ── */
      document.getElementById('hero-flag').textContent = emoji;
      document.getElementById('hero-code').textContent = code;
      document.getElementById('hero-name').textContent = nameEn + ' · ' + nameVi;

      /* ── 3. Hero quick-rate chips (base → 4 targets) ── */
      const baseData = ASEAN.find(c => c.code === code);
      if (baseData) {
        const targets = Object.keys(baseData.rates).slice(0, 4);
        document.getElementById('hero-rates').innerHTML = targets.map(t => {
          const val = baseData.rates[t];
          return `<div class="bh-rate-chip">
                <div class="brc-label">1 ${code} =</div>
                <div class="brc-val">${fmtRate(val)}</div>
                <div class="brc-cur">${t}</div>
              </div>`;
        }).join('');
      }

      /* ── 4. Alert bar ── */
      const alertEl = document.getElementById('alert-base-text');
      if (alertEl) alertEl.textContent = code + ' (' + nameEn + ')';

      /* ── 5. Table headers (both directions) ── */
      const th3 = document.getElementById('th-base-to-target');
      const th4 = document.getElementById('th-target-to-base');
      if (th3) th3.textContent = '1 ' + code + ' =';
      if (th4) th4.textContent = '1 [Target] = ' + code;

      /* ── 6. Modal add rate: base label ── */
      const modalBase = document.getElementById('modal-base-code');
      if (modalBase) modalBase.textContent = code;

      /* ── 7. Matrix base label ── */
      const matrixLabel = document.getElementById('matrix-base-label');
      if (matrixLabel) matrixLabel.textContent = code;

      /* ── 8. Rebuild rates table ── */
      rebuildRatesTable(code);

      /* ── 9. Rebuild chart pair dropdown ── */
      rebuildChartPairs(code);

      /* ── 10. Re-render matrix if visible ── */
      if (document.getElementById('tab-matrix').classList.contains('active')) {
        renderMatrix();
      }

      /* ── 11. Re-render chart if visible ── */
      if (document.getElementById('tab-charts').classList.contains('active')) {
        setTimeout(renderChart, 50);
      }
    }

    /* ════════════════════════════════════════════════════
       REBUILD RATES TABLE
       Shows: Base → each of 9 ASEAN peers, plus inverse
    ════════════════════════════════════════════════════ */
    function rebuildRatesTable(baseCode) {
      const baseData = ASEAN.find(c => c.code === baseCode);
      if (!baseData) return;

      const tbody = document.getElementById('rates-tbody');
      tbody.innerHTML = ASEAN
        .filter(c => c.code !== baseCode)
        .map(c => {
          const fwd = baseData.rates[c.code];   /* 1 BASE → TARGET */
          const rev = fwd ? (1 / fwd) : null;   /* 1 TARGET → BASE */
          const ch24 = CHG24[c.code] || 0;
          const ch7d = CHG7D[c.code] || 0;
          const up24 = ch24 >= 0, up7 = ch7d >= 0;

          return `<tr data-code="${c.code}" data-country="${c.country}">
                <td>
                  <div style="display:flex;align-items:center;gap:11px;">
                    <img class="flag-img" src="https://flagcdn.com/w80/${c.flag}.png" alt="${c.country}"
                         onerror="this.style.display='none'">
                    <span style="font-weight:600;font-size:13px;font-family:'DM Sans',sans-serif;">${c.country}</span>
                  </div>
                </td>
                <td>
                  <div class="cur-code">${c.code}</div>
                  <div class="cur-name">${c.name}</div>
                </td>
                <td>
                  <div class="rate-primary">${fmtRate(fwd)} ${c.code}</div>
                  <div class="rate-inverse">1 ${baseCode} → ${c.code}</div>
                </td>
                <td>
                  <div class="rate-primary">${fmtRate(rev)} ${baseCode}</div>
                  <div class="rate-inverse">1 ${c.code} → ${baseCode}</div>
                </td>
                <td>
                  <span class="chg-pill ${up24 ? 'chg-up' : 'chg-down'}">
                    <i class="fas fa-arrow-${up24 ? 'up' : 'down'}"></i>${Math.abs(ch24).toFixed(2)}%
                  </span>
                </td>
                <td>
                  <span class="chg-pill ${up7 ? 'chg-up' : 'chg-down'}">
                    <i class="fas fa-arrow-${up7 ? 'up' : 'down'}"></i>${Math.abs(ch7d).toFixed(2)}%
                  </span>
                </td>
                <td>
                  <label class="toggle">
                    <input type="checkbox" checked onchange="toggleRateStatus(0)">
                    <span class="toggle-track"></span>
                  </label>
                </td>
                <td>
                  <div style="display:flex;gap:6px;justify-content:flex-end;">
                    <button class="btn-icon-glass" title="Chỉnh sửa"><i class="fas fa-pen"></i></button>
                    <button class="btn-icon-glass text-danger" title="Xóa"><i class="fas fa-trash-alt"></i></button>
                  </div>
                </td>
              </tr>`;
        }).join('');
    }

    /* ════════════════════════════════════════════════════
       CROSS-RATE MATRIX
       10×10 direct pairs — no USD pivot
    ════════════════════════════════════════════════════ */
    function renderMatrix() {
      const codes = ASEAN.map(c => c.code);
      let html = '<thead><tr>';
      html += '<th style="text-align:left;min-width:54px;background:var(--bg-page);">↓ / →</th>';

      codes.forEach(c => {
        const isBase = c === currentBase;
        html += `<th class="${isBase ? 'mtx-th-base' : ''}" style="min-width:76px;">${c}</th>`;
      });
      html += '</tr></thead><tbody>';

      codes.forEach(rowC => {
        const rowData = ASEAN.find(x => x.code === rowC);
        const isRowBase = rowC === currentBase;
        const rowBg = isRowBase
          ? 'background:rgba(var(--accent-rgb,29,158,117),0.09);'
          : 'background:var(--bg-page);';

        html += `<tr><th style="text-align:left;white-space:nowrap;${rowBg}font-family:'IBM Plex Mono',monospace;">${rowC}</th>`;

        codes.forEach(colC => {
          if (rowC === colC) {
            html += `<td class="mtx-self">—</td>`;
          } else {
            const rate = rowData?.rates[colC];
            const isHighlight = rowC === currentBase || colC === currentBase;
            const val = fmtRate(rate);
            html += `<td class="${isHighlight ? 'mtx-base' : ''}">${val}</td>`;
          }
        });
        html += '</tr>';
      });

      html += '</tbody>';
      document.getElementById('matrix-table').innerHTML = html;
    }

    /* ════════════════════════════════════════════════════
       CHART PAIR DROPDOWN
       Pairs are always Base → each ASEAN peer
    ════════════════════════════════════════════════════ */
    function rebuildChartPairs(baseCode) {
      const sel = document.getElementById('chart-pair');
      if (!sel) return;
      const peers = ASEAN.filter(c => c.code !== baseCode);
      sel.innerHTML = peers.map(c => `<option value="${baseCode}/${c.code}">${baseCode} / ${c.code}</option>`).join('');
    }

    /* ════════════════════════════════════════════════════
       CHART RENDER
    ════════════════════════════════════════════════════ */
    /* sample data generator (replace with real API data) */
    function generateChartData(period) {
      const counts = { '1N': 14, '1T': 30, '1TH': 12, '3TH': 13, '1NM': 12 };
      const labels1N = ['T2', 'T3', 'T4', 'T5', 'T6', 'T7', 'CN', 'T2', 'T3', 'T4', 'T5', 'T6', 'T7', 'CN'];
      const labels1T = Array.from({ length: 30 }, (_, i) => `${i + 1}`);
      const labelsM = ['T1', 'T2', 'T3', 'T4', 'T5', 'T6', 'T7', 'T8', 'T9', 'T10', 'T11', 'T12'];

      const labelMap = { '1N': labels1N, '1T': labels1T, '1TH': labelsM, '3TH': ['Q1T1', 'Q1T2', 'Q1T3', 'Q2T1', 'Q2T2', 'Q2T3', 'Q3T1', 'Q3T2', 'Q3T3', 'Q4T1', 'Q4T2', 'Q4T3'], '1NM': labelsM };

      const n = counts[period] || 14;
      const base = 716;
      const data = Array.from({ length: n }, (_, i) => +(base + (Math.random() - 0.48) * 12 + i * 0.5).toFixed(2));
      return { labels: labelMap[period] || labels1N, data };
    }

    function renderChart() {
      if (chartInstance) { chartInstance.destroy(); chartInstance = null; }
      const el = document.querySelector('#chartPreview');
      if (!el) return;

      const pair = document.getElementById('chart-pair')?.value || (currentBase + ' / VND');
      const activeTypeBtn = document.querySelector('.chart-type-btn.active');
      const chartType = activeTypeBtn ? activeTypeBtn.dataset.type : 'area';
      const { labels, data } = generateChartData(currentPeriod);

      document.getElementById('chart-title').textContent = pair + ' · Biểu đồ tỷ giá nội khối';

      const opts = {
        series: [{ name: pair, data }],
        chart: {
          height: 320,
          type: chartType === 'area' ? 'area' : chartType,
          toolbar: { show: false },
          fontFamily: 'IBM Plex Mono, monospace',
          background: 'transparent',
          animations: { enabled: true, easing: 'easeinout', speed: 400 }
        },
        colors: ['var(--accent, #1D9E75)'],
        stroke: { curve: 'smooth', width: chartType === 'bar' ? 0 : 2.5 },
        fill: chartType === 'area'
          ? { type: 'gradient', gradient: { shadeIntensity: 1, opacityFrom: 0.14, opacityTo: 0.01, stops: [0, 100] } }
          : { opacity: 1 },
        dataLabels: { enabled: false },
        xaxis: {
          categories: labels,
          labels: { style: { colors: '#9298A8', fontSize: '11px' } },
          axisBorder: { show: false },
          axisTicks: { show: false }
        },
        yaxis: {
          labels: {
            style: { colors: '#9298A8', fontSize: '11px' },
            formatter: v => v.toLocaleString('vi-VN')
          }
        },
        grid: { borderColor: 'var(--border, #E4E7ED)', strokeDashArray: 4 },
        tooltip: { theme: 'light', x: { show: true } }
      };

      chartInstance = new ApexCharts(el, opts);
      chartInstance.render();
    }

    function setTimePeriod(btn, period) {
      document.querySelectorAll('.time-pill').forEach(b => b.classList.remove('active'));
      btn.classList.add('active');
      currentPeriod = period;
      renderChart();
    }

    /* ════════════════════════════════════════════════════
       FILTER TABLE
    ════════════════════════════════════════════════════ */
    function filterRates(val) {
      document.querySelectorAll('#rates-tbody tr').forEach(row => {
        const code = (row.dataset.code || '').toLowerCase();
        const country = (row.dataset.country || '').toLowerCase();
        const q = val.toLowerCase();
        row.style.display = (!q || code.includes(q) || country.includes(q)) ? '' : 'none';
      });
    }

    /* ════════════════════════════════════════════════════
       TOGGLE STATUS
    ════════════════════════════════════════════════════ */
    function toggleRateStatus(id) {
      if (!id) return;
      fetch(`/admin/rates/${id}/toggle`, {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': '{{ csrf_token() }}',
          'Content-Type': 'application/json'
        }
      });
    }

    /* ════════════════════════════════════════════════════
       CMS COUNTRY SELECT
    ════════════════════════════════════════════════════ */
    function selectCmsCountry(el, code, country) {
      document.querySelectorAll('#cms-country-grid .cc').forEach(c => c.classList.remove('active'));
      el.classList.add('active');
      document.getElementById('cms-country-input').value = code;
      const lbl = document.getElementById('cms-label-code');
      if (lbl) lbl.textContent = code;
    }

    /* ════════════════════════════════════════════════════
       COPY TO CLIPBOARD
    ════════════════════════════════════════════════════ */
    function copyCode(text, btn) {
      navigator.clipboard.writeText(text).then(() => {
        btn.innerHTML = '<i class="fas fa-check"></i>';
        btn.style.color = 'var(--success)';
        setTimeout(() => { btn.innerHTML = '<i class="far fa-copy"></i>'; btn.style.color = ''; }, 1800);
      });
    }

    /* ════════════════════════════════════════════════════
       INIT
    ════════════════════════════════════════════════════ */
    document.addEventListener('DOMContentLoaded', () => {
      /* Chart type buttons */
      document.querySelectorAll('.chart-type-btn').forEach(btn => {
        btn.addEventListener('click', function () {
          document.querySelectorAll('.chart-type-btn').forEach(b => b.classList.remove('active'));
          this.classList.add('active');
        });
      });

      /* Init chart pair dropdown for default base */
      rebuildChartPairs(currentBase);
    });
  </script>
@endpush