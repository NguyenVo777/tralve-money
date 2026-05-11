@extends('layouts.app')
@section('title', 'Trang Cá Nhân')

@push('styles')
    <style>
        .profile-page {
            padding: 14px 16px;
            max-width: 100%;
            margin: 0;
            height: calc(100vh - 64px);
            display: flex;
            flex-direction: column;
            overflow: hidden;
            box-sizing: border-box;
        }

        .profile-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 12px;
            flex-shrink: 0;
            gap: 8px;
            flex-wrap: wrap;
        }

        .profile-header h1 { font-size: 20px; font-weight: 800; line-height: 1.2; }
        .profile-header p  { color: var(--text-muted, #6b7a99); font-size: 12px; margin-top: 2px; }

        .btn-admin {
            display: inline-flex; align-items: center; gap: 7px;
            padding: 8px 16px; border-radius: 10px;
            background: linear-gradient(135deg, #4f46e5, #7c3aed);
            color: #fff; font-weight: 700; font-size: 13px;
            text-decoration: none; transition: opacity .2s; flex-shrink: 0;
        }
        .btn-admin:hover { opacity: .85; }

        .p-alert {
            padding: 10px 16px; border-radius: 10px; font-size: 13px; font-weight: 600;
            display: flex; align-items: center; gap: 8px; margin-bottom: 10px; flex-shrink: 0;
        }
        .p-alert.success { background: rgba(16,185,129,.1); color: #10b981; border: 1px solid rgba(16,185,129,.2); }
        .p-alert.error   { background: rgba(239,68,68,.1);  color: #ef4444;  border: 1px solid rgba(239,68,68,.2); }

        .profile-grid {
            display: grid;
            grid-template-columns: 268px 1fr;
            gap: 14px;
            flex: 1;
            min-height: 0;
            overflow: hidden;
        }

        /* SIDEBAR */
        .sidebar-col {
            display: flex; flex-direction: column; gap: 10px;
            overflow-y: auto; min-height: 0; padding-right: 2px;
            scrollbar-width: thin; scrollbar-color: rgba(255,255,255,.08) transparent;
        }
        .sidebar-col::-webkit-scrollbar { width: 3px; }
        .sidebar-col::-webkit-scrollbar-thumb { background: rgba(255,255,255,.1); border-radius: 3px; }

        .p-card {
            background: var(--bg-surface, #111827);
            border: 1px solid var(--border, rgba(255,255,255,.08));
            border-radius: 14px; overflow: hidden; flex-shrink: 0;
        }

        .avatar-block {
            padding: 20px 18px 16px; text-align: center;
            border-bottom: 1px solid var(--border, rgba(255,255,255,.06));
        }
        .avatar-ring { position: relative; width: 76px; height: 76px; margin: 0 auto 10px; }
        .avatar-ring img { width: 76px; height: 76px; border-radius: 50%; object-fit: cover; border: 3px solid var(--primary, #4f46e5); }
        .avatar-edit {
            position: absolute; bottom: 0; right: 0; width: 24px; height: 24px;
            border-radius: 50%; background: var(--primary, #4f46e5); color: #fff;
            border: 2px solid var(--bg-surface, #111827);
            display: flex; align-items: center; justify-content: center;
            font-size: 9px; cursor: pointer; transition: .2s;
        }
        .avatar-edit:hover { transform: scale(1.12); }
        .av-name  { font-size: 15px; font-weight: 800; margin-bottom: 2px; }
        .av-email { font-size: 11px; color: var(--text-muted, #6b7a99); word-break: break-all; }
        .av-badge {
            display: inline-flex; align-items: center; gap: 4px;
            margin-top: 7px; padding: 3px 10px; border-radius: 20px;
            font-size: 10px; font-weight: 700; letter-spacing: .05em;
            background: rgba(79,70,229,.15); color: #4f46e5;
            border: 1px solid rgba(79,70,229,.25);
        }

        .sb-form { padding: 14px 16px; }
        .sb-label {
            font-size: 10px; font-weight: 700; color: var(--text-muted, #6b7a99);
            letter-spacing: .07em; text-transform: uppercase;
            margin-bottom: 10px; display: flex; align-items: center; gap: 5px;
        }
        .frow { margin-bottom: 10px; }
        .frow label { display: block; font-size: 11px; font-weight: 600; color: var(--text-muted, #6b7a99); margin-bottom: 4px; }
        .fc {
            width: 100%; padding: 8px 11px;
            background: rgba(0,0,0,.25);
            border: 1px solid var(--border, rgba(255,255,255,.08));
            border-radius: 8px; color: var(--text-main, #e8eaf6);
            font-size: 13px; font-family: inherit; transition: .2s; box-sizing: border-box;
        }
        .fc:focus { outline: none; border-color: #4f46e5; box-shadow: 0 0 0 2px rgba(79,70,229,.15); }
        .fc option { background: #1a2235; }

        .divider { height: 1px; background: var(--border, rgba(255,255,255,.06)); margin: 10px 0; }

        .sbtn {
            width: 100%; padding: 9px 14px; border-radius: 8px; border: none; cursor: pointer;
            font-size: 13px; font-weight: 700; font-family: inherit;
            display: flex; align-items: center; justify-content: center; gap: 7px; transition: all .25s;
        }
        .sbtn-pri { background: linear-gradient(135deg,#4f46e5,#7c3aed); color: #fff; }
        .sbtn-pri:hover { opacity: .88; transform: translateY(-1px); }
        .sbtn-sec { background: rgba(255,255,255,.06); color: var(--text-main,#e8eaf6); border: 1px solid var(--border,rgba(255,255,255,.08)); }
        .sbtn-sec:hover { background: rgba(255,255,255,.1); }
        .sbtn-red { background: rgba(239,68,68,.1); color: #ef4444; border: 1px solid rgba(239,68,68,.2); }
        .sbtn-red:hover { background: rgba(239,68,68,.18); }
        .sbtn+.sbtn { margin-top: 7px; }

        /* MAIN */
        .main-col { display: flex; flex-direction: column; gap: 10px; min-height: 0; overflow: hidden; }

        .stats-row { display: grid; grid-template-columns: repeat(3,1fr); gap: 10px; flex-shrink: 0; }
        .stat-mini {
            background: var(--bg-surface,#111827);
            border: 1px solid var(--border,rgba(255,255,255,.08));
            border-radius: 12px; padding: 14px; display: flex; align-items: center; gap: 12px;
        }
        .sm-icon { width: 38px; height: 38px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 16px; flex-shrink: 0; }
        .sm-icon.pu { background: rgba(79,70,229,.15); color: #4f46e5; }
        .sm-icon.gr { background: rgba(16,185,129,.15); color: #10b981; }
        .sm-icon.am { background: rgba(245,158,11,.15);  color: #f59e0b; }
        .sm-lbl { font-size: 11px; color: var(--text-muted,#6b7a99); font-weight: 600; margin-bottom: 3px; }
        .sm-val { font-size: 20px; font-weight: 800; line-height: 1; }
        .sm-val.sm { font-size: 13px; padding-top: 2px; }

        .activity-card { flex: 1; min-height: 0; display: flex; flex-direction: column; overflow: hidden; }

        .tab-nav { display: flex; border-bottom: 1px solid var(--border,rgba(255,255,255,.06)); padding: 0 18px; flex-shrink: 0; }
        .tnav-btn {
            padding: 12px 14px; border: none; background: transparent;
            color: var(--text-muted,#6b7a99); font-size: 13px; font-weight: 600; font-family: inherit;
            cursor: pointer; border-bottom: 2px solid transparent; margin-bottom: -1px; transition: .2s;
            display: flex; align-items: center; gap: 6px;
        }
        .tnav-btn.active { color: #4f46e5; border-bottom-color: #4f46e5; }
        .tnav-btn:hover:not(.active) { color: var(--text-main,#e8eaf6); }
        .tcnt { padding: 1px 6px; border-radius: 20px; font-size: 10px; font-weight: 700; }
        .tcnt.pu { background: rgba(79,70,229,.15); color: #4f46e5; }
        .tcnt.gr { background: rgba(16,185,129,.15); color: #10b981; }

        .tab-body {
            display: none; flex: 1; overflow-y: auto; padding: 16px 18px;
            scrollbar-width: thin; scrollbar-color: rgba(255,255,255,.08) transparent;
        }
        .tab-body::-webkit-scrollbar { width: 4px; }
        .tab-body::-webkit-scrollbar-thumb { background: rgba(255,255,255,.1); border-radius: 4px; }
        .tab-body.active { display: block; }

        .dtable { width: 100%; border-collapse: collapse; }
        .dtable th { text-align: left; font-size: 10px; font-weight: 700; color: var(--text-muted,#6b7a99); letter-spacing: .06em; text-transform: uppercase; padding: 0 10px 10px; border-bottom: 1px solid var(--border,rgba(255,255,255,.06)); }
        .dtable td { padding: 10px; font-size: 12px; border-bottom: 1px solid rgba(255,255,255,.025); vertical-align: middle; }
        .dtable tr:last-child td { border-bottom: none; }
        .dtable tr:hover td { background: rgba(255,255,255,.02); }

        .empty { text-align: center; padding: 36px 20px; color: var(--text-muted,#6b7a99); }
        .empty .ei { font-size: 36px; margin-bottom: 10px; }
        .empty p { font-size: 13px; line-height: 1.6; }

        .bdg { display: inline-flex; align-items: center; padding: 2px 8px; border-radius: 20px; font-size: 10px; font-weight: 700; }
        .bdg-ok   { background: rgba(16,185,129,.12); color: #10b981; }
        .bdg-warn { background: rgba(245,158,11,.12);  color: #f59e0b; }
        .bdg-bad  { background: rgba(239,68,68,.12);   color: #ef4444; }
        .bdg-pu   { background: rgba(79,70,229,.12);   color: #4f46e5; }

        @media (max-width: 960px) {
            .profile-page { height: auto; overflow: visible; }
            .profile-grid { grid-template-columns: 1fr; overflow: visible; }
            .sidebar-col, .main-col, .activity-card, .tab-body { overflow: visible; height: auto; }
            .stats-row { grid-template-columns: 1fr 1fr; }
        }
        @media (max-width: 560px) {
            .stats-row { grid-template-columns: 1fr; }
        }
    </style>
@endpush

@section('content')
    <div class="profile-page">

        @if(session('success'))
            <div class="p-alert success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="p-alert error"><i class="fas fa-exclamation-circle"></i> {{ session('error') }}</div>
        @endif
        @if($errors->any())
            <div class="p-alert error"><i class="fas fa-triangle-exclamation"></i> {{ $errors->first() }}</div>
        @endif

        <div class="profile-header">
            <div>
                <h1>👤 Trang Cá Nhân</h1>
                <p>Quản lý thông tin tài khoản và lịch sử hoạt động</p>
            </div>
            @if(Auth::user()->isAdmin())
                <a href="{{ route('admin.dashboard') }}" class="btn-admin">
                    <i class="fas fa-user-shield"></i> Admin Panel
                </a>
            @endif
        </div>

        <div class="profile-grid">

            {{-- SIDEBAR --}}
            <div class="sidebar-col">
                <div class="p-card">
                    <div class="avatar-block">
                        <div class="avatar-ring">
                            <img id="avatar-preview"
                                src="{{ $user->avatar ? asset('storage/'.$user->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($user->full_name).'&background=4f46e5&color=fff&size=160' }}"
                                alt="Avatar">
                            <input type="file" id="avatar-input" name="avatar" accept=".png,.jpg,.jpeg"
                                style="display:none" onchange="previewAvatar(this)">
                            <label for="avatar-input" class="avatar-edit" title="Đổi ảnh">
                                <i class="fas fa-pen"></i>
                            </label>
                        </div>
                        <div class="av-name">{{ $user->full_name }}</div>
                        <div class="av-email">{{ $user->email }}</div>
                        <div class="av-badge">
                            <i class="fas fa-{{ $user->isAdmin() ? 'shield-halved' : 'user' }}"></i>
                            {{ $user->isAdmin() ? 'Quản trị viên' : 'Thành viên' }}
                        </div>
                    </div>
                    <div class="sb-form">
                        <div class="sb-label"><i class="fas fa-pen-to-square"></i> Thông tin cá nhân</div>
                        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="frow">
                                <label>Họ và tên</label>
                                <input type="text" name="full_name" value="{{ old('full_name',$user->full_name) }}" class="fc" placeholder="Nhập họ tên...">
                            </div>
                            <div class="frow">
                                <label>Số điện thoại</label>
                                <input type="text" name="phone" value="{{ old('phone',$user->phone) }}" class="fc" placeholder="Nhập số điện thoại...">
                            </div>
                            <div class="frow">
                                <label>Quốc gia</label>
                                <select name="country" class="fc">
                                    @foreach(['Vietnam','Thailand','Indonesia','Singapore','Malaysia','Japan','Korea','USA','UK','Australia'] as $c)
                                        <option value="{{ $c }}" {{ ($user->country ?? 'Vietnam')==$c ? 'selected' : '' }}>{{ $c }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="frow">
                                <label>Ngôn ngữ</label>
                                <select name="language" class="fc">
                                    <option value="vi" {{ ($user->language ?? 'vi')=='vi' ? 'selected' : '' }}>🇻🇳 Tiếng Việt</option>
                                    <option value="en" {{ ($user->language ?? 'vi')=='en' ? 'selected' : '' }}>🇺🇸 English</option>
                                    <option value="th" {{ ($user->language ?? 'vi')=='th' ? 'selected' : '' }}>🇹🇭 Thai</option>
                                    <option value="ja" {{ ($user->language ?? 'vi')=='ja' ? 'selected' : '' }}>🇯🇵 日本語</option>
                                </select>
                            </div>
                            <button type="submit" class="sbtn sbtn-pri">
                                <i class="fas fa-save"></i> Lưu thay đổi
                            </button>
                        </form>
                    </div>
                </div>

                <div class="p-card">
                    <div class="sb-form">
                        <div class="sb-label"><i class="fas fa-gear"></i> Cài đặt</div>
                        <button onclick="toggleDarkMode()" class="sbtn sbtn-sec">
                            <i class="fas {{ session('dark_mode') ? 'fa-sun' : 'fa-moon' }}"></i>
                            {{ session('dark_mode') ? 'Chế độ sáng' : 'Chế độ tối' }}
                        </button>
                        <div class="divider"></div>
                        <div class="sb-label" style="margin-top:4px"><i class="fas fa-lock"></i> Đổi mật khẩu</div>
                        <form action="{{ route('profile.password') }}" method="POST">
                            @csrf
                            <div class="frow">
                                <label>Mật khẩu hiện tại</label>
                                <input type="password" name="current_password" class="fc" placeholder="••••••••">
                            </div>
                            <div class="frow">
                                <label>Mật khẩu mới</label>
                                <input type="password" name="new_password" class="fc" placeholder="Tối thiểu 8 ký tự">
                            </div>
                            <div class="frow">
                                <label>Xác nhận mật khẩu mới</label>
                                <input type="password" name="new_password_confirmation" class="fc" placeholder="••••••••">
                            </div>
                            <button type="submit" class="sbtn sbtn-pri">
                                <i class="fas fa-key"></i> Cập nhật mật khẩu
                            </button>
                        </form>
                        <div class="divider"></div>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="sbtn sbtn-red">
                                <i class="fas fa-right-from-bracket"></i> Đăng xuất
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            {{-- MAIN --}}
            <div class="main-col">
                <div class="stats-row">
                    <div class="stat-mini">
                        <div class="sm-icon pu"><i class="fas fa-camera"></i></div>
                        <div>
                            <div class="sm-lbl">Lượt quét AI</div>
                            <div class="sm-val">{{ $user->scans->count() }}</div>
                        </div>
                    </div>
                    <div class="stat-mini">
                        <div class="sm-icon gr"><i class="fas fa-money-bill-transfer"></i></div>
                        <div>
                            <div class="sm-lbl">Lần đổi tiền</div>
                            <div class="sm-val">{{ $user->conversions->count() }}</div>
                        </div>
                    </div>
                    <div class="stat-mini">
                        <div class="sm-icon am"><i class="fas fa-calendar-check"></i></div>
                        <div>
                            <div class="sm-lbl">Ngày tham gia</div>
                            <div class="sm-val sm">{{ $user->created_at->format('d/m/Y') }}</div>
                        </div>
                    </div>
                </div>

                <div class="p-card activity-card">
                    <div class="tab-nav">
                        <button class="tnav-btn active" onclick="switchTab('scans',this)">
                            <i class="fas fa-camera"></i> Quét AI
                            <span class="tcnt pu">{{ $user->scans->count() }}</span>
                        </button>
                        <button class="tnav-btn" onclick="switchTab('conversions',this)">
                            <i class="fas fa-exchange-alt"></i> Đổi tiền
                            <span class="tcnt gr">{{ $user->conversions->count() }}</span>
                        </button>
                        <button class="tnav-btn" onclick="switchTab('logs',this)">
                            <i class="fas fa-list-ul"></i> Nhật ký
                        </button>
                    </div>

                    <div id="tab-scans" class="tab-body active">
                        @if($user->scans->isEmpty())
                            <div class="empty"><div class="ei">📷</div><p>Chưa có lượt quét nào.<br>Hãy thử tính năng Quét AI!</p></div>
                        @else
                            <table class="dtable">
                                <thead><tr><th>Thời gian</th><th>Loại tiền</th><th>Độ chính xác</th><th>Kết quả</th></tr></thead>
                                <tbody>
                                    @foreach($user->scans as $scan)
                                    <tr>
                                        <td style="color:var(--text-muted);white-space:nowrap">{{ $scan->created_at->format('d/m/Y H:i') }}</td>
                                        <td><strong>{{ $scan->currency }}</strong></td>
                                        <td><span class="bdg {{ $scan->accuracy>=90?'bdg-ok':($scan->accuracy>=70?'bdg-warn':'bdg-bad') }}">{{ $scan->accuracy }}%</span></td>
                                        <td>{{ $scan->result ?? '—' }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @endif
                    </div>

                    <div id="tab-conversions" class="tab-body">
                        @if($user->conversions->isEmpty())
                            <div class="empty"><div class="ei">💱</div><p>Chưa có lịch sử đổi tiền.<br>Hãy thử trang Chuyển đổi!</p></div>
                        @else
                            <table class="dtable">
                                <thead><tr><th>Thời gian</th><th>Từ</th><th>Sang</th><th>Số tiền gốc</th><th>Kết quả</th></tr></thead>
                                <tbody>
                                    @foreach($user->conversions as $conv)
                                    <tr>
                                        <td style="color:var(--text-muted);white-space:nowrap">{{ $conv->created_at->format('d/m/Y H:i') }}</td>
                                        <td><span class="bdg bdg-pu">{{ $conv->from_currency }}</span></td>
                                        <td><span class="bdg bdg-ok">{{ $conv->to_currency }}</span></td>
                                        <td>{{ number_format($conv->amount_from ?? $conv->amount) }}</td>
                                        <td><strong>{{ number_format($conv->amount_to ?? $conv->result) }}</strong></td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @endif
                    </div>

                    <div id="tab-logs" class="tab-body">
                        @if($user->activityLogs->isEmpty())
                            <div class="empty"><div class="ei">📋</div><p>Chưa có nhật ký hoạt động.</p></div>
                        @else
                            <table class="dtable">
                                <thead><tr><th>Thời gian</th><th>Hành động</th><th>IP Address</th></tr></thead>
                                <tbody>
                                    @foreach($user->activityLogs as $log)
                                    <tr>
                                        <td style="color:var(--text-muted);white-space:nowrap">{{ $log->created_at->format('d/m/Y H:i') }}</td>
                                        <td>{{ $log->action }}</td>
                                        <td style="font-family:monospace;font-size:11px;color:var(--text-muted)">{{ $log->ip_address ?? '—' }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @endif
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function switchTab(tabId, btn) {
            document.querySelectorAll('.tab-body').forEach(t => t.classList.remove('active'));
            document.querySelectorAll('.tnav-btn').forEach(b => b.classList.remove('active'));
            document.getElementById('tab-' + tabId).classList.add('active');
            btn.classList.add('active');
        }
        function previewAvatar(input) {
            if (input.files && input.files[0]) {
                const r = new FileReader();
                r.onload = e => document.getElementById('avatar-preview').src = e.target.result;
                r.readAsDataURL(input.files[0]);
            }
        }
        function toggleDarkMode() {
            fetch('{{ route("profile.dark-mode") }}', {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json' }
            }).then(r => r.json()).then(() => location.reload());
        }
    </script>
@endpush