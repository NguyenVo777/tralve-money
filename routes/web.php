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