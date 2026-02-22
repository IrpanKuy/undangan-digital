<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\Admin\KategoriUndangan;
use App\Http\Controllers\Admin\TemplateUndangan;
use App\Http\Controllers\Admin\TemplateContentUndanganController;

Route::get('/', function () {
    return Inertia::render('welcome'); // 'Welcome' merujuk ke Welcome.vue
});

Route::prefix('admin')->name('admin.')->group(function () {
    Route::resource('kategori-undangan', KategoriUndangan::class);
    Route::resource('template-undangan', TemplateUndangan::class);
    
    // Resource route for template-content-undangan (handles index, store, update, destroy)
    Route::resource('template-content-undangan', TemplateContentUndanganController::class);
    
    // Custom routes for create content and setting
    Route::get('template-content-undangan/create/content', [TemplateContentUndanganController::class, 'createContent'])
        ->name('template-content-undangan.create-content');
    Route::get('template-content-undangan/create/setting', [TemplateContentUndanganController::class, 'createSetting'])
        ->name('template-content-undangan.create-setting');
        
    // Custom routes for edit content and setting
    Route::get('template-content-undangan/{id}/edit/content', [TemplateContentUndanganController::class, 'editContent'])
        ->name('template-content-undangan.edit-content');
    Route::get('template-content-undangan/{id}/edit/setting', [TemplateContentUndanganController::class, 'editSetting'])
        ->name('template-content-undangan.edit-setting');
    Route::put('template-content-undangan/{id}/update-setting', [TemplateContentUndanganController::class, 'updateSetting'])
        ->name('template-content-undangan.update-setting');

    Route::patch('template-content-undangan/{id}/toggle-status', [TemplateContentUndanganController::class, 'toggleStatus'])
        ->name('template-content-undangan.toggle-status');
    Route::post('template-content-undangan/check-url', [TemplateContentUndanganController::class, 'checkUrl'])
        ->name('template-content-undangan.check-url');
});

