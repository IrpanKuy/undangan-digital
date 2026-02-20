<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\Admin\KategoriUndangan;
use App\Http\Controllers\Admin\TemplateUndangan;

Route::get('/', function () {
    return Inertia::render('welcome'); // 'Welcome' merujuk ke Welcome.vue
});

Route::prefix('admin')->name('admin.')->group(function () {
    Route::resource('kategori-undangan', KategoriUndangan::class);
    Route::resource('template-undangan', TemplateUndangan::class);
});
