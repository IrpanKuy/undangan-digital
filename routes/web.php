<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\Admin\KategoriUndangan;
use App\Http\Controllers\Admin\TemplateUndangan;
use App\Http\Controllers\api\bulkShareWaController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\MainUndanganController;
use App\Http\Controllers\RoutingUndanganController;
use App\Http\Controllers\UndanganPreviewController;
use App\Http\Controllers\User\DashboardController;
use App\Http\Controllers\User\ListUndanganController;
use App\Http\Controllers\User\PengaturanTambahanController;
use App\Http\Controllers\User\UndanganController;
use Laravel\Socialite\Socialite;

Route::get('/', function () {
    return Inertia::render('welcome'); // 'Welcome' merujuk ke Welcome.vue
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
    Route::delete('template-content-undangan/{id}/destroy', [MainUndanganController::class, 'destroy'])
        ->name('template-content-undangan.destroy');
    Route::patch('template-content-undangan/{id}/toggle-status', [MainUndanganController::class, 'toggleStatus'])
        ->name('template-content-undangan.toggle-status');
    Route::post('template-content-undangan/check-url', [MainUndanganController::class, 'checkUrl'])
        ->name('template-content-undangan.check-url');
    Route::post('template-content-undangan', [MainUndanganController::class, 'store'])->name('template-content-undangan.store');

});

Route::middleware('hasAuth')->name('user.')->group(function () {
    Route::post('bulk-share-wa', [bulkShareWaController::class, 'bulkShareWa'])->name('bulk.share.wa');
    // preview route
    Route::get('undangan/template-preview/{judul_undangan}', [UndanganPreviewController::class, 'preview'])->name('undangan.preview');
    Route::post('undangan/preview', [UndanganPreviewController::class, 'previewCreateEdit'])->name('undangan.preview.create-edit');

    Route::get('undangan/{id}/edit/content', [RoutingUndanganController::class, 'editContent'])
        ->name('undangan.edit-content');
    Route::get('undangan/{id}/edit/setting', [RoutingUndanganController::class, 'editSetting'])
        ->name('undangan.edit-setting');
    Route::get('undangan/create/content/{template_id}', [UndanganController::class, 'contentForm'])->name('undangan.create.content');
    Route::get('undangan/create/setting', [UndanganController::class, 'SettingForm'])->name('undangan.create.setting');
    
    Route::get('undangan/pilih-template', [ListUndanganController::class, 'pilihTemplate'])->name('undangan.pilih-template');
    Route::get('undangan', [UndanganController::class, 'index'])->name('undangan.index');
    Route::get('undangan/{activeMenu}/{undanganId}/pengaturan-tambahan', [PengaturanTambahanController::class, 'index'])->name('undangan.pengaturan-tambahan');
    Route::post('undangan/{undanganId}/kontak', [PengaturanTambahanController::class, 'storeKontak'])->name('undangan.kontak.store');
    Route::put('undangan/kontak/{id}', [PengaturanTambahanController::class, 'updateKontak'])->name('undangan.kontak.update');
    Route::delete('undangan/kontak/{id}', [PengaturanTambahanController::class, 'destroyKontak'])->name('undangan.kontak.destroy');
    Route::post('undangan/{undanganId}/kontak/mark-sent', [PengaturanTambahanController::class, 'markSentKontak'])->name('undangan.kontak.markSent');
    Route::post('undangan/{undanganId}/komentar/{komentarId}/reply', [PengaturanTambahanController::class, 'replyKomentar'])->name('undangan.komentar.reply');
    Route::post('undangan/{undanganId}/komentar/{komentarId}/like', [PengaturanTambahanController::class, 'toggleLike'])->name('undangan.komentar.like');
});

// Public Undangan Routes (No Auth Needed)
Route::get('undangan/{url}', [UndanganPreviewController::class, 'previewUser'])->name('undangan.preview.user')->middleware('trackVisitor');
Route::post('undangan/{undangan_id}/komentar', [App\Http\Controllers\User\UndanganPublicController::class, 'storeKomentar'])->name('undangan.komentar.store');
Route::post('undangan/{undangan_id}/reservasi', [App\Http\Controllers\User\UndanganPublicController::class, 'storeReservasi'])->name('undangan.reservasi.store');



