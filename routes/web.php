<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\Admin\KategoriUndangan;
use App\Http\Controllers\Admin\TemplateUndangan;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\MainUndanganController;
use App\Http\Controllers\RoutingUndanganController;
use App\Http\Controllers\UndanganPreviewController;
use App\Http\Controllers\User\DashboardController;
use App\Http\Controllers\User\ListUndanganController;
use App\Http\Controllers\User\UndanganController;
use Laravel\Socialite\Socialite;

Route::get('/', function () {
    return Inertia::render('templateUndangan/Pernikahan/blue-coral'); // 'Welcome' merujuk ke Welcome.vue
})->name('home');

Route::get('/auth/google', [AuthController::class, 'redirectToGoogleRegister'])->name('google.login');
Route::get('/auth/google/callback', [AuthController::class, 'handleGoogleCallback'])->name('google.callback');

// Auth Routes
Route::middleware('notAuth')->group(function () {
    Route::get('login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('login', [AuthController::class, 'login']);
    Route::get('register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('register', [AuthController::class, 'register']);
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');
    
    // Google Login Placeholder
    // Route::get('auth/google', [AuthController::class, 'redirectToGoogle'])->name('google.login');
});

Route::post('logout', [AuthController::class, 'logout'])->name('logout');

Route::prefix('admin')->middleware('hasAuth')->name('admin.')->group(function () {
    Route::resource('kategori-undangan', KategoriUndangan::class);
    Route::resource('template-undangan', TemplateUndangan::class);
    
    // route store
    Route::get('template-content-undangan', [RoutingUndanganController::class, 'indexAdmin'])->name('template-content-undangan.index');
    // Custom routes for create content and setting
    Route::get('template-content-undangan/create/content', [RoutingUndanganController::class, 'createContentAdmin'])
        ->name('template-content-undangan.create-content');
    Route::get('template-content-undangan/create/setting', [RoutingUndanganController::class, 'createSettingAdmin'])
        ->name('template-content-undangan.create-setting');
        
    // Custom routes for edit content and setting
    Route::get('template-content-undangan/{id}/edit/content', [RoutingUndanganController::class, 'editContent'])
        ->name('template-content-undangan.edit-content');
    Route::get('template-content-undangan/{id}/edit/setting', [RoutingUndanganController::class, 'editSetting'])
        ->name('template-content-undangan.edit-setting');
    Route::put('template-content-undangan/{id}/update-setting', [MainUndanganController::class, 'updateSetting'])
        ->name('template-content-undangan.update-setting');

    Route::patch('template-content-undangan/{id}/toggle-status', [MainUndanganController::class, 'toggleStatus'])
        ->name('template-content-undangan.toggle-status');
    Route::post('template-content-undangan/check-url', [MainUndanganController::class, 'checkUrl'])
        ->name('template-content-undangan.check-url');
    Route::post('template-content-undangan', [MainUndanganController::class, 'store'])->name('template-content-undangan.store');

});

Route::prefix('user')->middleware('hasAuth')->name('user.')->group(function () {
    Route::get('undangan/content', [UndanganController::class, 'contentForm'])->name('undangan.content');
    Route::get('undangan/setting', [UndanganController::class, 'SettingForm'])->name('undangan.setting');
    Route::get('preview/{judul_undangan}', [UndanganPreviewController::class, 'preview'])->name('preview');
    Route::get('undangan/pilih-template', [ListUndanganController::class, 'pilihTemplate'])->name('undangan.pilih-template');
    Route::resource('undangan', UndanganController::class);
    
});



