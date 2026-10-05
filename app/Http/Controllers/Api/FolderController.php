<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Folder;
use App\Models\File;
use Illuminate\Http\Request;

class FolderController extends Controller
{
    // Menampilkan daftar folder dan file di Root (ketika folderId null)
    public function index(Request $request)
    {
        // Mengambil folder tingkat root (parent_id null)
        $folders = Folder::with(['user:id,name'])->whereNull('parent_id')->get();

        // Mengambil file tingkat root (folder_id null) jika model File sudah ada
        $files = class_exists(File::class) ? File::whereNull('folder_id')->get() : [];

        return response()->json([
            'success' => true,
            'data' => [
                'folders' => $folders,
                'files' => $files
            ]
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
            'user_id' => $request->user()->id,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Folder berhasil dibuat',
            'data' => $folder
        ], 201);
    }

    // Menampilkan detail folder beserta isi sub-folder (children) dan file di dalamnya
    public function show(Folder $folder)
    {
        // Memuat relasi anak folder dan file di dalam folder ini
        $folder->load(['children', 'files', 'user:id,name', 'parent']);

        return response()->json([
            'success' => true,
            'data' => [
                'current_folder' => $folder,
                'children' => $folder->children, // Sub-folder
                'files' => $folder->files        // File di dalam folder ini
            ]
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
        $folder->delete();

        return response()->json([
            'success' => true,
            'message' => 'Folder berhasil dihapus'
        ]);
    }

    public function allFolders()
    {
        $folders = Folder::select('id', 'name', 'parent_id')->orderBy('name', 'asc')->get();

        return response()->json([
            'success' => true,
            'data' => $folders
        ]);
    }
}