<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\AdminController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/dashboard/streamer', [DashboardController::class, 'addStreamer'])->name('dashboard.add');
    Route::delete('/dashboard/streamer/{streamer}', [DashboardController::class, 'deleteStreamer'])->name('dashboard.delete');
    Route::post('/dashboard/streamer/{streamer}/toggle', [DashboardController::class, 'toggleMonitoring'])->name('dashboard.toggle');
    Route::post('/dashboard/streamer/{streamer}/check', [DashboardController::class, 'checkNow'])->name('dashboard.check');
    Route::get('/settings', [DashboardController::class, 'settings'])->name('settings');
    Route::post('/settings', [DashboardController::class, 'updateSettings'])->name('settings.update');
});

Route::prefix('admin')->group(function () {
    Route::middleware('guest:admin')->group(function () {
        Route::get('/login', function () {
            return view('admin.login');
        })->name('admin.login');
        Route::post('/login', function (\Illuminate\Http\Request $request) {
            $credentials = $request->validate([
                'email' => 'required|email',
                'password' => 'required',
            ]);

            if (Auth::guard('admin')->attempt($credentials)) {
                $request->session()->regenerate();
                return redirect()->intended(route('admin.dashboard'));
            }

            return back()->withErrors([
                'email' => '邮箱或密码错误',
            ]);
        });
    });

    Route::middleware('auth:admin')->group(function () {
        Route::post('/logout', function (\Illuminate\Http\Request $request) {
            Auth::guard('admin')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect()->route('admin.login');
        })->name('admin.logout');

        Route::get('/', [AdminController::class, 'index'])->name('admin.dashboard');
        Route::get('/users', [AdminController::class, 'users'])->name('admin.users');
        Route::post('/users/{user}/toggle', [AdminController::class, 'toggleUserStatus'])->name('admin.users.toggle');
        Route::delete('/users/{user}', [AdminController::class, 'deleteUser'])->name('admin.users.delete');
        Route::get('/streamers', [AdminController::class, 'streamers'])->name('admin.streamers');
        Route::delete('/streamers/{streamer}', [AdminController::class, 'deleteStreamer'])->name('admin.streamers.delete');
        Route::get('/logs', [AdminController::class, 'logs'])->name('admin.logs');
    });
});