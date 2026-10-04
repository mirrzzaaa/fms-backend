<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    // Menampilkan daftar file (Mendukung pencarian & filter opsional berdasarkan soal)
    public function index(Request $request)
    {
        $query = File::with(['folder:id,name', 'department:id,name', 'user:id,name']);

        // Filter berdasarkan pencarian nama file atau title
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('original_filename', 'like', "%{$search}%");
            });
        }

        // Filter berdasarkan department
        if ($request->has('department_id') && $request->department_id != '') {
            $query->where('department_id', $request->department_id);
        }

        // Filter berdasarkan folder tertentu
        if ($request->has('folder_id') && $request->folder_id != '') {
            $query->where('folder_id', $request->folder_id);
        }

        // Pagination sesuai technical requirements
        $files = $query->latest()->paginate(10);

        return response()->json([
            'success' => true,
            'data' => $files
        ]);
    }

    // Mengunggah file baru (Khusus Administrator)
    public function store(Request $request)
    {
        $request->validate([
            'folder_id' => 'required|exists:folders,id',
            'department_id' => 'required|exists:departments,id',
            'title' => 'required|string|max:255',
            'file' => 'required|file|mimes:pdf,doc,docx,xls,xlsx,jpg,png,jpeg|max:10240', // Maksimal 10MB
        ]);

        $uploadedFile = $request->file('file');
        $originalFilename = $uploadedFile->getClientOriginalName();
        $fileSize = $uploadedFile->getSize(); // Dalam bytes

        // Simpan file ke storage (folder public/uploads)
        $filePath = $uploadedFile->store('uploads/files', 'public');

        $file = File::create([
            'folder_id' => $request->folder_id,
            'department_id' => $request->department_id,
            'user_id' => $request->user()->id,
            'title' => $request->title,
            'original_filename' => $originalFilename,
            'file_path' => $filePath,
            'file_size' => $fileSize,
        ]);

        $file->load(['folder:id,name', 'department:id,name', 'user:id,name']);

        return response()->json([
            'success' => true,
            'message' => 'File berhasil diunggah',
            'data' => $file
        ], 201);
    }

    // Menampilkan detail file (Bisa diakses Admin & Viewer)
    public function show(File $file)
    {
        $file->load(['folder:id,name', 'department:id,name', 'user:id,name']);

        return response()->json([
            'success' => true,
            'data' => $file
        ]);
    }

    // Mengubah informasi file (Title & Department / Khusus Administrator)
    public function update(Request $request, File $file)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'department_id' => 'required|exists:departments,id',
            'folder_id' => 'required|exists:folders,id',
        ]);

        $file->update([
            'title' => $request->title,
            'department_id' => $request->department_id,
            'folder_id' => $request->folder_id,
        ]);

        $file->load(['folder:id,name', 'department:id,name', 'user:id,name']);

        return response()->json([
            'success' => true,
            'message' => 'Informasi file berhasil diperbarui',
            'data' => $file
        ]);
    }

    // Menghapus file (Khusus Administrator)
    public function destroy(File $file)
    {
        // Hapus file fisik dari storage
        if (Storage::disk('public')->exists($file->file_path)) {
            Storage::disk('public')->delete($file->file_path);
        }

        $file->delete();

        return response()->json([
            'success' => true,
            'message' => 'File berhasil dihapus'
        ]);
    }

    // Download file (Bisa diakses Viewer & Admin)
    public function download(File $file)
    {
        $path = storage_path('app/public/' . $file->file_path);

        if (!file_exists($path)) {
            return response()->json([
                'success' => false,
                'message' => 'File fisik tidak ditemukan di server'
            ], 404);
        }

        return response()->download($path, $file->original_filename);
    }
}
