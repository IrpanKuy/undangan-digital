<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\KategoriUndangan as KategoriUndanganModel;
use Illuminate\Http\Request;
use Inertia\Inertia;

class KategoriUndangan extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $kategori = KategoriUndanganModel::latest()->get();

        return Inertia::render('admin/kategoriUndangan', [
            'kategori' => $kategori,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'   => 'required|string|max:255|unique:kategori_undangans,name',
            'status' => 'required|boolean',
        ], [
            'name.required' => 'Nama kategori wajib diisi.',
            'name.unique'   => 'Nama kategori sudah ada.',
            'status.required' => 'Status wajib dipilih.',
        ]);

        KategoriUndanganModel::create([
            'name'   => $request->name,
            'status' => $request->status,
        ]);

        return redirect()->route('admin.kategori-undangan.index')
            ->with('success', 'Kategori undangan berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $kategori = KategoriUndanganModel::findOrFail($id);

        $request->validate([
            'name'   => 'required|string|max:255|unique:kategori_undangans,name,' . $id,
            'status' => 'required|boolean',
        ], [
            'name.required' => 'Nama kategori wajib diisi.',
            'name.unique'   => 'Nama kategori sudah ada.',
            'status.required' => 'Status wajib dipilih.',
        ]);

        $kategori->update([
            'name'   => $request->name,
            'status' => $request->status,
        ]);

        return redirect()->route('admin.kategori-undangan.index')
            ->with('success', 'Kategori undangan berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $kategori = KategoriUndanganModel::findOrFail($id);
        $kategori->delete();

        return redirect()->route('admin.kategori-undangan.index')
            ->with('success', 'Kategori undangan berhasil dihapus.');
    }
}
