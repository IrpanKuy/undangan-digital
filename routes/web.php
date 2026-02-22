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
    Route::resource('template-content-undangan', TemplateContentUndanganController::class);
    Route::patch('template-content-undangan/{id}/toggle-status', [TemplateContentUndanganController::class, 'toggleStatus'])
        ->name('template-content-undangan.toggle-status');
});

