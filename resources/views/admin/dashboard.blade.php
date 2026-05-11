@extends('admin.layout')

@section('title', 'Overview Dashboard')
@section('header_title', 'Dashboard')

@section('content')

    {{-- ── STAT CARDS ── --}}
    <div class="stats-grid">

        <div class="premium-card">
            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:4px;">
                <span class="text-muted"
                    style="font-size:12px;font-weight:600;letter-spacing:.04em;text-transform:uppercase;">Tổng người
                    dùng</span>
                <div
                    style="width:34px;height:34px;border-radius:10px;background:var(--accent-light);display:flex;align-items:center;justify-content:center;">
                    <i class="fas fa-users" style="color:var(--accent);font-size:14px;"></i>
                </div>
            </div>
            <div class="stat-value">{{ number_format($stats['total_users']) }}</div>
            <div class="stat-change change-up">
                <i class="fas fa-arrow-up"></i> +12% trong 30 ngày
            </div>
        </div>

        <div class="premium-card">
            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:4px;">
                <span class="text-muted"
                    style="font-size:12px;font-weight:600;letter-spacing:.04em;text-transform:uppercase;">Tổng lượt quét
                    AI</span>
                <div
                    style="width:34px;height:34px;border-radius:10px;background:#FEF3C7;display:flex;align-items:center;justify-content:center;">
                    <i class="fas fa-brain" style="color:#D97706;font-size:14px;"></i>
                </div>
            </div>
            <div class="stat-value">{{ number_format($stats['total_scans']) }}</div>
            <div class="stat-change change-up">
                <i class="fas fa-arrow-up"></i> +25% tuần này
            </div>
        </div>

        <div class="premium-card">
            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:4px;">
                <span class="text-muted"
                    style="font-size:12px;font-weight:600;letter-spacing:.04em;text-transform:uppercase;">Tổng giao
                    dịch</span>
                <div
                    style="width:34px;height:34px;border-radius:10px;background:#F3E8FF;display:flex;align-items:center;justify-content:center;">
                    <i class="fas fa-exchange-alt" style="color:#7C3AED;font-size:14px;"></i>
                </div>
            </div>
            <div class="stat-value">{{ number_format($stats['total_conversions']) }}</div>
            <div class="stat-change change-down">
                <i class="fas fa-arrow-down"></i> -3% hôm nay
            </div>
        </div>

        <div class="premium-card">
            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:4px;">
                <span class="text-muted"
                    style="font-size:12px;font-weight:600;letter-spacing:.04em;text-transform:uppercase;">Độ chính xác
                    AI</span>
                <div
                    style="width:34px;height:34px;border-radius:10px;background:var(--success-bg);display:flex;align-items:center;justify-content:center;">
                    <i class="fas fa-bullseye" style="color:var(--success);font-size:14px;"></i>
                </div>
            </div>
            <div class="stat-value">99.85%</div>
            <div class="stat-change change-up">
                <i class="fas fa-shield-alt"></i> Tối ưu hóa cực cao
            </div>
        </div>

    </div>

    {{-- ── CHARTS ROW ── --}}
    <div style="display:grid;grid-template-columns:1fr 340px;gap:16px;margin-bottom:20px;">

        <!-- Growth Chart -->
        <div class="section-card">
            <div class="section-card-head">
                <h4>Biểu đồ Tăng trưởng Hệ thống</h4>
                <div style="display:flex;gap:6px;">
                    <button class="btn-tab-sm active">7N</button>
                    <button class="btn-tab-sm">1T</button>
                    <button class="btn-tab-sm">Tất cả</button>
                </div>
            </div>
            <div style="padding:20px;">
                <div id="growthChart" style="height:300px;"></div>
            </div>
        </div>

        <!-- Countries Pie -->
        <div class="section-card">
            <div class="section-card-head">
                <h4>Quốc gia hoạt động</h4>
            </div>
            <div style="padding:16px;">
                <div id="countriesPie" style="height:220px;"></div>
                <div style="margin-top:16px;display:flex;flex-direction:column;gap:10px;">
                    <div style="display:flex;justify-content:space-between;align-items:center;">
                        <div style="display:flex;align-items:center;gap:8px;">
                            <div style="width:8px;height:8px;border-radius:2px;background:var(--accent);"></div>
                            <span style="font-size:13px;">Vietnam</span>
                        </div>
                        <span style="font-size:13px;font-weight:600;color:var(--accent);">45%</span>
                    </div>
                    <div style="display:flex;justify-content:space-between;align-items:center;">
                        <div style="display:flex;align-items:center;gap:8px;">
                            <div style="width:8px;height:8px;border-radius:2px;background:#7C3AED;"></div>
                            <span style="font-size:13px;">Thailand</span>
                        </div>
                        <span style="font-size:13px;font-weight:600;color:#7C3AED;">25%</span>
                    </div>
                    <div style="display:flex;justify-content:space-between;align-items:center;">
                        <div style="display:flex;align-items:center;gap:8px;">
                            <div style="width:8px;height:8px;border-radius:2px;background:var(--warning);"></div>
                            <span style="font-size:13px;">Indonesia</span>
                        </div>
                        <span style="font-size:13px;font-weight:600;color:var(--warning);">15%</span>
                    </div>
                    <div style="display:flex;justify-content:space-between;align-items:center;">
                        <div style="display:flex;align-items:center;gap:8px;">
                            <div style="width:8px;height:8px;border-radius:2px;background:var(--txt3);"></div>
                            <span style="font-size:13px;">Khác</span>
                        </div>
                        <span style="font-size:13px;font-weight:600;color:var(--txt3);">15%</span>
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- ── RECENT SCANS ── --}}
    <div class="section-card">
        <div class="section-card-head">
            <h4>Lượt Quét AI Gần Đây</h4>
            <a href="#" class="btn-secondary" style="font-size:12px;padding:6px 14px;">Xem tất cả <i
                    class="fas fa-arrow-right" style="font-size:10px;"></i></a>
        </div>
        <div class="premium-table-container">
            <table class="premium-table">
                <thead>
                    <tr>
                        <th>Người dùng</th>
                        <th>Loại tiền</th>
                        <th>Độ chính xác</th>
                        <th>Thời gian</th>
                        <th>Trạng thái</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($realtime['new_scans_list'] ?? [] as $scan)
                        <tr>
                            <td>
                                <div style="display:flex;align-items:center;gap:10px;">
                                    <div
                                        style="width:32px;height:32px;border-radius:8px;background:var(--accent-light);display:flex;align-items:center;justify-content:center;">
                                        <i class="fas fa-user" style="font-size:12px;color:var(--accent);"></i>
                                    </div>
                                    <span style="font-weight:500;">{{ $scan->user->full_name }}</span>
                                </div>
                            </td>
                            <td>
                                <span class="badge badge-accent">{{ $scan->currency }}</span>
                            </td>
                            <td>
                                <div style="display:flex;align-items:center;gap:10px;min-width:120px;">
                                    <div class="progress-track">
                                        <div class="progress-fill" style="width:{{ $scan->accuracy }}%;"></div>
                                    </div>
                                    <span
                                        style="font-size:12px;font-weight:600;color:var(--txt);min-width:36px;">{{ $scan->accuracy }}%</span>
                                </div>
                            </td>
                            <td style="color:var(--txt3);">{{ $scan->created_at->diffForHumans() }}</td>
                            <td>
                                <span class="badge badge-success">
                                    <i class="fas fa-check" style="font-size:9px;"></i> Thành công
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {

            // Growth Chart
            new ApexCharts(document.querySelector('#growthChart'), {
                series: [
                    { name: 'Lượt quét', data: [31, 40, 28, 51, 42, 109, 100] },
                    { name: 'Người dùng mới', data: [11, 32, 45, 32, 34, 52, 41] }
                ],
                chart: {
                    height: 300, type: 'area',
                    background: 'transparent',
                    foreColor: '#9298A8',
                    toolbar: { show: false },
                    fontFamily: 'DM Sans, sans-serif',
                },
                colors: ['#4F46E5', '#7C3AED'],
                fill: {
                    type: 'gradient',
                    gradient: { shadeIntensity: 1, opacityFrom: 0.15, opacityTo: 0.01 }
                },
                dataLabels: { enabled: false },
                stroke: { curve: 'smooth', width: 2.5 },
                xaxis: {
                    categories: ['T2', 'T3', 'T4', 'T5', 'T6', 'T7', 'CN'],
                    axisBorder: { show: false },
                    axisTicks: { show: false },
                },
                grid: { borderColor: '#E4E7ED', strokeDashArray: 4 },
                tooltip: { theme: 'light' },
                legend: {
                    position: 'top', horizontalAlign: 'right',
                    markers: { width: 8, height: 8, radius: 2 }
                }
            }).render();

            // Pie Chart
            new ApexCharts(document.querySelector('#countriesPie'), {
                series: [45, 25, 15, 15],
                chart: { type: 'donut', height: 220, fontFamily: 'DM Sans, sans-serif' },
                labels: ['Vietnam', 'Thailand', 'Indonesia', 'Khác'],
                colors: ['#4F46E5', '#7C3AED', '#D97706', '#9298A8'],
                plotOptions: {
                    pie: {
                        donut: {
                            size: '72%',
                            labels: {
                                show: true,
                                total: {
                                    show: true, label: 'Tổng', color: '#9298A8',
                                    formatter: () => '100%'
                                }
                            }
                        }
                    }
                },
                dataLabels: { enabled: false },
                stroke: { width: 0 },
                legend: { show: false },
                tooltip: { theme: 'light' }
            }).render();

            // Tab buttons
            document.querySelectorAll('.btn-tab-sm').forEach(btn => {
                btn.addEventListener('click', function () {
                    document.querySelectorAll('.btn-tab-sm').forEach(b => b.classList.remove('active'));
                    this.classList.add('active');
                });
            });

        });
    </script>
@endpush