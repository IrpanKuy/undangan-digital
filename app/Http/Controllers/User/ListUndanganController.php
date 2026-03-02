<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Admin\TemplateUndangan;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ListUndanganController extends Controller
{
    public function pilihTemplate()
    {
        $listTemplate = TemplateUndangan::where('is_active', 1)->with(['kategoriUndangan'])->latest()->get();
        return Inertia::render('user/undangan/pilihTemplate', [
            'listTemplate' => $listTemplate
        ]);
    }
}