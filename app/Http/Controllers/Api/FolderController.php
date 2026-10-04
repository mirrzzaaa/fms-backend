<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Folder;
use Illuminate\Http\Request;

class FolderController extends Controller
{
    // Menampilkan daftar folder (Bisa memfilter berdasarkan parent_id untuk hierarki)
    public function index(Request $request)
    {
        $query = Folder::with(['user:id,name', 'children', 'files']);

        // Jika ada parameter parent_id, ambil sub-folder dari parent tersebut. Jika tidak, ambil Root Folder (null)
        if ($request->has('parent_id')) {
            $parentId = $request->parent_id === 'null' ? null : $request->parent_id;
            $query->where('parent_id', $parentId);
        } else {
            // Default tampilkan root folder
            $query->whereNull('parent_id');
        }

        $folders = $query->get();

        return response()->json([
            'success' => true,
            'data' => $folders
        ]);
    }

    // Membuat folder baru (Khusus Administrator)
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:folders,id',
        ]);

        $folder = Folder::create([
            'name' => $request->name,
            'parent_id' => $request->parent_id ?? null,
            'user_id' => $request->user()->id, // Mengambil ID admin yang sedang login
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Folder berhasil dibuat',
            'data' => $folder
        ], 201);
    }

    // Menampilkan detail folder beserta isi sub-folder dan file di dalamnya
    public function show(Folder $folder)
    {
        $folder->load(['children', 'files.department', 'user:id,name', 'parent']);

        return response()->json([
            'success' => true,
            'data' => $folder
        ]);
    }

    // Mengubah nama folder (Khusus Administrator)
    public function update(Request $request, Folder $folder)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $folder->update([
            'name' => $request->name,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Folder berhasil diperbarui',
            'data' => $folder
        ]);
    }

    // Menghapus folder (Khusus Administrator)
    public function destroy(Folder $folder)
    {
        // Berdasarkan migration, onDelete('cascade') akan otomatis menghapus sub-folder dan file di dalamnya
        $folder->delete();

        return response()->json([
            'success' => true,
            'message' => 'Folder berhasil dihapus'
        ]);
    }
}
