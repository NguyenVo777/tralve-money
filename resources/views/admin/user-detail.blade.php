@extends('admin.layout')

@section('title', 'Chi Tiết Người Dùng')
@section('header_title', 'Chi Tiết Người Dùng')

@section('content')

    {{-- Back + title --}}
    <div class="dashboard-header">
        <a href="{{ route('admin.users') }}" class="btn-icon-glass" style="width:36px;height:36px;">
            <i class="fas fa-arrow-left"></i>
        </a>
        <div>
            <h1 style="font-family:'DM Serif Display',serif;font-size:22px;font-weight:400;color:var(--txt);">
                {{ $user->full_name }}
            </h1>
            <p style="font-size:12px;color:var(--txt3);">Tham gia {{ $user->created_at->format('d/m/Y') }}</p>
        </div>
    </div>

    <div class="profile-grid">

        {{-- ── Profile Card ── --}}
        <div class="profile-card">
            <div style="position:relative;display:inline-block;">
                <img src="{{ $user->avatar ? asset('storage/' . $user->avatar) : asset('images/default-avatar.png') }}"
                    class="avatar-preview" alt="{{ $user->full_name }}">
                <span class="status-dot {{ $user->status == 'active' ? 'active' : 'locked' }}"
                    style="position:absolute;bottom:4px;right:4px;width:12px;height:12px;border:2px solid white;"></span>
            </div>

            <h2 style="margin:14px 0 6px;">{{ $user->full_name }}</h2>

            <span class="status-badge {{ $user->status == 'active' ? 'status-active' : 'status-locked' }}">
                <i class="fas fa-circle" style="font-size:7px;"></i>
                {{ $user->status == 'active' ? 'Đang hoạt động' : 'Đã khóa' }}
            </span>

            <div class="profile-info-list">
                <div class="info-item">
                    <span class="info-label"><i class="fas fa-envelope"
                            style="width:14px;margin-right:4px;color:var(--txt3);"></i> Email</span>
                    <span class="info-value">{{ $user->email }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label"><i class="fas fa-phone"
                            style="width:14px;margin-right:4px;color:var(--txt3);"></i> Điện thoại</span>
                    <span class="info-value">{{ $user->phone ?? 'Chưa cập nhật' }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label"><i class="fas fa-globe"
                            style="width:14px;margin-right:4px;color:var(--txt3);"></i> Quốc gia</span>
                    <span class="info-value">{{ $user->country ?? 'Chưa cập nhật' }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label"><i class="fas fa-language"
                            style="width:14px;margin-right:4px;color:var(--txt3);"></i> Ngôn ngữ</span>
                    <span class="info-value">
                        <span class="badge badge-accent">{{ strtoupper($user->language) }}</span>
                    </span>
                </div>
                <div class="info-item">
                    <span class="info-label"><i class="fas fa-user-tag"
                            style="width:14px;margin-right:4px;color:var(--txt3);"></i> Vai trò</span>
                    <span class="info-value">
                        <span class="badge {{ $user->role == 'admin' ? 'badge-accent' : '' }}"
                            style="{{ $user->role != 'admin' ? 'background:var(--bg-page);color:var(--txt2);' : '' }}">
                            {{ strtoupper($user->role) }}
                        </span>
                    </span>
                </div>
                <div class="info-item">
                    <span class="info-label"><i class="fas fa-calendar"
                            style="width:14px;margin-right:4px;color:var(--txt3);"></i> Tham gia</span>
                    <span class="info-value">{{ $user->created_at->format('d/m/Y H:i') }}</span>
                </div>
            </div>

            {{-- Quick actions --}}
            <div style="margin-top:20px;display:flex;flex-direction:column;gap:8px;">
                <form action="{{ route('admin.users.toggle', $user->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn-secondary" style="width:100%;justify-content:center;">
                        @if($user->status == 'active')
                            <i class="fas fa-user-slash"></i> Khóa tài khoản
                        @else
                            <i class="fas fa-user-check"></i> Mở khóa tài khoản
                        @endif
                    </button>
                </form>
                <form action="{{ route('admin.users.delete', $user->id) }}" method="POST"
                    onsubmit="return confirm('Xóa người dùng này?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn-secondary"
                        style="width:100%;justify-content:center;color:var(--danger);border-color:#FECACA;">
                        <i class="fas fa-trash"></i> Xóa tài khoản
                    </button>
                </form>
            </div>
        </div>

        {{-- ── History ── --}}
        <div class="history-tabs" style="display:flex;flex-direction:column;gap:16px;">

            {{-- Activity Log --}}
            <div class="data-card">
                <div class="data-header" style="display:flex;align-items:center;justify-content:space-between;">
                    <h3>Lịch sử hoạt động</h3>
                    <span class="badge badge-info">{{ $user->activityLogs->count() }} bản ghi</span>
                </div>
                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th>Thời gian</th>
                                <th>Hành động</th>
                                <th>Chi tiết</th>
                                <th>IP Address</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($user->activityLogs as $log)
                                <tr>
                                    <td style="color:var(--txt3);white-space:nowrap;">
                                        {{ $log->created_at->format('d/m/Y H:i') }}
                                    </td>
                                    <td><span class="badge badge-accent">{{ $log->action }}</span></td>
                                    <td style="color:var(--txt2);">{{ $log->details }}</td>
                                    <td style="font-family:monospace;font-size:12px;color:var(--txt3);">{{ $log->ip_address }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" style="text-align:center;padding:24px;color:var(--txt3);">
                                        <i class="fas fa-inbox"
                                            style="font-size:24px;display:block;margin-bottom:8px;opacity:.4;"></i>
                                        Chưa có lịch sử hoạt động
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Login Log --}}
            <div class="data-card">
                <div class="data-header" style="display:flex;align-items:center;justify-content:space-between;">
                    <h3>Lịch sử đăng nhập</h3>
                    <span class="badge badge-info">{{ $user->loginLogs->count() }} lần</span>
                </div>
                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th>Thời gian</th>
                                <th>IP Address</th>
                                <th>Thiết bị</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($user->loginLogs as $log)
                                <tr>
                                    <td style="color:var(--txt3);white-space:nowrap;">
                                        {{ $log->created_at->format('d/m/Y H:i') }}
                                    </td>
                                    <td style="font-family:monospace;font-size:12px;">{{ $log->ip_address }}</td>
                                    <td
                                        style="max-width:240px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;color:var(--txt2);font-size:12px;">
                                        {{ $log->user_agent }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" style="text-align:center;padding:24px;color:var(--txt3);">
                                        <i class="fas fa-sign-in-alt"
                                            style="font-size:24px;display:block;margin-bottom:8px;opacity:.4;"></i>
                                        Chưa có lịch sử đăng nhập
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

@endsection