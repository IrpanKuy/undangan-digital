<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('welcome'); // 'Welcome' merujuk ke Welcome.vue
});

// Route::get('/', function () {
//     return view('welcome');
// });
