@extends('admin.layout')

@section('title', 'Quản Lý Người Dùng')
@section('header_title', 'Người Dùng')

@section('content')

    {{-- ── STAT CARDS ── --}}
    <div class="stats-grid" style="grid-template-columns:repeat(3,1fr);">

        <div class="premium-card">
            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:4px;">
                <span class="text-muted"
                    style="font-size:12px;font-weight:600;letter-spacing:.04em;text-transform:uppercase;">Tổng thành
                    viên</span>
                <div
                    style="width:34px;height:34px;border-radius:10px;background:var(--accent-light);display:flex;align-items:center;justify-content:center;">
                    <i class="fas fa-users" style="color:var(--accent);font-size:14px;"></i>
                </div>
            </div>
            <div class="stat-value">{{ number_format($users->total()) }}</div>
            <div style="font-size:12px;color:var(--txt3);margin-top:4px;">Tất cả thành viên</div>
        </div>

        <div class="premium-card">
            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:4px;">
                <span class="text-muted"
                    style="font-size:12px;font-weight:600;letter-spacing:.04em;text-transform:uppercase;">Thành viên mới
                    (24h)</span>
                <div
                    style="width:34px;height:34px;border-radius:10px;background:var(--success-bg);display:flex;align-items:center;justify-content:center;">
                    <i class="fas fa-user-plus" style="color:var(--success);font-size:14px;"></i>
                </div>
            </div>
            <div class="stat-value change-up">+12</div>
            <div style="font-size:12px;color:var(--txt3);margin-top:4px;">Trong 24 giờ qua</div>
        </div>

        <div class="premium-card">
            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:4px;">
                <span class="text-muted"
                    style="font-size:12px;font-weight:600;letter-spacing:.04em;text-transform:uppercase;">Tài khoản bị
                    khóa</span>
                <div
                    style="width:34px;height:34px;border-radius:10px;background:var(--danger-bg);display:flex;align-items:center;justify-content:center;">
                    <i class="fas fa-user-slash" style="color:var(--danger);font-size:14px;"></i>
                </div>
            </div>
            <div class="stat-value change-down">5</div>
            <div style="font-size:12px;color:var(--txt3);margin-top:4px;">Cần xem xét</div>
        </div>

    </div>

    {{-- ── TABLE CARD ── --}}
    <div class="section-card">

        {{-- Head / Filters --}}
        <div class="section-card-head" style="flex-wrap:wrap;gap:12px;">
            <div style="display:flex;gap:10px;align-items:center;flex-wrap:wrap;">
                <div class="header-search" style="width:260px;">
                    <i class="fas fa-search"></i>
                    <input type="text" id="userSearch" placeholder="Tìm tên, email...">
                </div>
                <select class="form-control" style="width:160px;">
                    <option value="">Tất cả vai trò</option>
                    <option value="admin">Admin</option>
                    <option value="user">User</option>
                </select>
                <select class="form-control" style="width:160px;">
                    <option value="">Tất cả trạng thái</option>
                    <option value="active">Hoạt động</option>
                    <option value="locked">Bị khóa</option>
                </select>
            </div>
            <button class="btn-glow">
                <i class="fas fa-user-plus"></i> Thêm Người Dùng
            </button>
        </div>

        {{-- Table --}}
        <div class="premium-table-container">
            <table class="premium-table">
                <thead>
                    <tr>
                        <th>Người dùng</th>
                        <th>Email</th>
                        <th>Quốc gia</th>
                        <th>Vai trò</th>
                        <th>Trạng thái</th>
                        <th style="text-align:right;">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                        <tr>
                            <td>
                                <div style="display:flex;align-items:center;gap:11px;">
                                    <img src="{{ $user->avatar ? asset('storage/' . $user->avatar) : asset('images/default-avatar.png') }}"
                                        style="width:36px;height:36px;border-radius:10px;object-fit:cover;border:1px solid var(--border);">
                                    <div>
                                        <div style="font-weight:600;font-size:13.5px;">{{ $user->full_name }}</div>
                                        <div style="font-size:11px;color:var(--txt3);">ID #{{ $user->id }}</div>
                                    </div>
                                </div>
                            </td>
                            <td style="color:var(--txt2);">{{ $user->email }}</td>
                            <td>
                                @if($user->country)
                                    <div style="display:flex;align-items:center;gap:6px;">
                                        <img src="https://flagcdn.com/w40/{{ strtolower($user->country_code ?? 'vn') }}.png"
                                            style="width:20px;height:14px;border-radius:3px;object-fit:cover;border:1px solid var(--border);">
                                        <span style="font-size:13px;">{{ $user->country }}</span>
                                    </div>
                                @else
                                    <span style="color:var(--txt3);">N/A</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge {{ $user->role == 'admin' ? 'badge-accent' : '' }}"
                                    style="{{ $user->role != 'admin' ? 'background:var(--bg-page);color:var(--txt2);' : '' }}">
                                    {{ strtoupper($user->role) }}
                                </span>
                            </td>
                            <td>
                                <div style="display:flex;align-items:center;gap:7px;">
                                    <span class="status-dot {{ $user->status == 'active' ? 'active' : 'locked' }}"></span>
                                    <span
                                        style="font-size:13px;font-weight:500;color:{{ $user->status == 'active' ? 'var(--success)' : 'var(--danger)' }};">
                                        {{ $user->status == 'active' ? 'Hoạt động' : 'Đã khóa' }}
                                    </span>
                                </div>
                            </td>
                            <td>
                                <div style="display:flex;gap:6px;justify-content:flex-end;">
                                    <button class="btn-icon-glass" title="Chi tiết"
                                        onclick="window.location='{{ route('admin.users.detail', $user->id) }}'">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <form action="{{ route('admin.users.toggle', $user->id) }}" method="POST">
                                        @csrf
                                        <button type="submit"
                                            class="btn-icon-glass {{ $user->status == 'active' ? 'text-danger' : 'text-success' }}"
                                            title="{{ $user->status == 'active' ? 'Khóa' : 'Mở khóa' }}">
                                            <i
                                                class="fas {{ $user->status == 'active' ? 'fa-user-slash' : 'fa-user-check' }}"></i>
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.users.delete', $user->id) }}" method="POST"
                                        onsubmit="return confirm('Bạn có chắc muốn xóa người dùng này?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-icon-glass text-danger" title="Xóa">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div style="padding:16px 22px;border-top:1px solid var(--border);">
            {{ $users->links() }}
        </div>

    </div>

@endsection