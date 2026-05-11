<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

Route::get('/', function () {
    return view('index');
})->name('home');

// ─── Auth: Login ──────────────────────────────────────────────────────────────

Route::get('/login', function () {
    if (Auth::check())
        return redirect()->route('home');
    return view('login');
})->name('login');

Route::post('/login', function (Request $request) {
    $credentials = $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required'],
    ], [
        'email.required' => 'Vui lòng nhập địa chỉ email.',
        'email.email' => 'Địa chỉ email không hợp lệ.',
        'password.required' => 'Vui lòng nhập mật khẩu.',
    ]);

    if (Auth::attempt($credentials)) {
        $request->session()->regenerate();
        
        if (Auth::user()->role === 'admin') {
            return redirect()->route('admin.dashboard')->with('success', 'Chào mừng Admin quay trở lại!');
        }
        
        return redirect()->intended(route('home'))->with('success', 'Đăng nhập thành công!');
    }

    return back()
        ->withInput($request->only('email'))
        ->with('error', 'Email hoặc mật khẩu không chính xác.');
});

Route::post('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect()->route('login');
})->name('logout');

// ─── Auth: Register ───────────────────────────────────────────────────────────

Route::get('/register', function () {
    if (Auth::check())
        return redirect()->route('home');
    return view('register');
})->name('register');

Route::post('/register', function (Request $request) {
    $request->validate([
        'full_name' => ['required', 'string', 'max:255'],
        'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
        'password' => ['required', 'string', 'confirmed'],
        'terms' => ['accepted'],
    ], [
        'full_name.required' => 'Vui lòng nhập họ và tên.',
        'full_name.max' => 'Họ và tên không được vượt quá 255 ký tự.',
        'email.required' => 'Vui lòng nhập địa chỉ email.',
        'email.email' => 'Địa chỉ email không hợp lệ.',
        'email.unique' => 'Email này đã được sử dụng.',
        'password.required' => 'Vui lòng nhập mật khẩu.',
        'password.min' => 'Mật khẩu phải có ít nhất 8 ký tự.',
        'password.confirmed' => 'Mật khẩu xác nhận không khớp.',
        'terms.accepted' => 'Bạn phải đồng ý với điều khoản dịch vụ.',
    ]);

    // Tạo user nhưng KHÔNG tự đăng nhập
    User::create([
        'full_name' => $request->full_name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
    ]);

    // Redirect sang trang login kèm thông báo thành công
    return redirect()->route('login')
        ->with('success', 'Đăng ký thành công! Vui lòng đăng nhập để tiếp tục.');
});

// ─── App routes ───────────────────────────────────────────────────────────────

Route::get('/scan', fn() => view('scan'))->name('scan');
Route::post('/scan', fn(Request $r) => redirect()->route('result'))->name('scan.post');
Route::get('/map', fn() => view('map'))->name('map');
use App\Http\Controllers\RateController;

use App\Http\Controllers\AnalysisController;

Route::get('/rates', [RateController::class, 'index'])->name('rates');
Route::post('/rates/store', [RateController::class, 'store'])->name('rates.store');
Route::get('/analysis', fn() => view('analysis'))->name('analysis');
Route::post('/analysis', [AnalysisController::class, 'analyze'])->name('analysis.post');
Route::get('/result', fn() => view('result'))->name('result');

// ─── Profile routes ─────────────────────────────────────────────────────────────

use App\Http\Controllers\ProfileController;

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/password', [ProfileController::class, 'changePassword'])->name('profile.password');
    Route::post('/profile/dark-mode', [ProfileController::class, 'toggleDarkMode'])->name('profile.dark-mode');
});

// ─── Admin routes ───────────────────────────────────────────────────────────────

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\RateCMSController;

Route::middleware(['auth', 'can:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/users', [DashboardController::class, 'users'])->name('users');
    Route::get('/users/{id}', [DashboardController::class, 'userDetail'])->name('users.detail');
    Route::post('/users/{id}/toggle', [DashboardController::class, 'toggleStatus'])->name('users.toggle');
    Route::delete('/users/{id}', [DashboardController::class, 'deleteUser'])->name('users.delete');
    
    // Rates CMS
    Route::get('/rates', [RateCMSController::class, 'index'])->name('rates');
    Route::post('/rates', [RateCMSController::class, 'store'])->name('rates.store');
    Route::put('/rates/{id}', [RateCMSController::class, 'update'])->name('rates.update');
    Route::delete('/rates/{id}', [RateCMSController::class, 'destroy'])->name('rates.delete');
    Route::post('/rates/{id}/toggle', [RateCMSController::class, 'toggleStatus'])->name('rates.toggle');
    Route::post('/rates/settings', [RateCMSController::class, 'updateSettings'])->name('rates.settings');
    Route::post('/rates/news', [RateCMSController::class, 'storeNews'])->name('rates.news.store');
    Route::delete('/rates/news/{id}', [RateCMSController::class, 'deleteNews'])->name('rates.news.delete');
    Route::post('/rates/update-rates', [RateCMSController::class, 'updateRates'])->name('rates.update-rates');
    Route::post('/rates/update-ai', [RateCMSController::class, 'updateAIAnalysis'])->name('rates.update-ai');
    Route::post('/rates/update-cms', [RateCMSController::class, 'autoUpdateCMS'])->name('rates.update-cms');
    Route::post('/rates/update-charts', [RateCMSController::class, 'autoUpdateCharts'])->name('rates.update-charts');
});