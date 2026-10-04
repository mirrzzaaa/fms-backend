<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    // Menampilkan daftar file
    public function index(Request $request)
    {
        $query = File::with(['folder:id,name', 'department:id,name', 'user:id,name']);

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('original_filename', 'like', "%{$search}%");
            });
        }

        if ($request->has('department_id') && $request->department_id != '') {
            $query->where('department_id', $request->department_id);
        }

        if ($request->has('folder_id') && $request->folder_id != '') {
            $query->where('folder_id', $request->folder_id);
        }

        $files = $query->latest()->paginate(10);

        return response()->json([
            'success' => true,
            'data' => $files
        ]);
    }

    // Mengunggah file baru
    public function store(Request $request)
    {
        $request->validate([
            'folder_id' => 'required|exists:folders,id',
            'department_id' => 'required|exists:departments,id',
            'title' => 'required|string|max:255',
            'file' => 'required|file|mimes:pdf,doc,docx,xls,xlsx,jpg,png,jpeg|max:10240',
        ]);

        $uploadedFile = $request->file('file');
        $originalFilename = $uploadedFile->getClientOriginalName();
        $fileSize = $uploadedFile->getSize();

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

    // Menampilkan detail file
    public function show(File $file)
    {
        $file->load(['folder:id,name', 'department:id,name', 'user:id,name']);

        return response()->json([
            'success' => true,
            'data' => $file
        ]);
    }

    // Mengubah informasi file
    public function update(Request $request, File $file)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'department_id' => 'required|exists:departments,id',
            'folder_id' => 'required|exists:folders,id',
            'file' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,jpg,png,jpeg|max:10240',
        ]);

        $data = [
            'title' => $request->title,
            'department_id' => $request->department_id,
            'folder_id' => $request->folder_id,
        ];

        // Jika ada file baru yang di-upload saat edit
        if ($request->hasFile('file')) {
            // Hapus file fisik lama
            if (Storage::disk('public')->exists($file->file_path)) {
                Storage::disk('public')->delete($file->file_path);
            }
            // Simpan file baru
            $uploadedFile = $request->file('file');
            $data['original_filename'] = $uploadedFile->getClientOriginalName();
            $data['file_path'] = $uploadedFile->store('uploads/files', 'public');
            $data['file_size'] = $uploadedFile->getSize();
        }

        $file->update($data);
        $file->load(['folder:id,name', 'department:id,name', 'user:id,name']);

        return response()->json([
            'success' => true,
            'message' => 'Informasi file berhasil diperbarui',
            'data' => $file
        ]);
    }

    // Menghapus file
    public function destroy(File $file)
    {
        if (Storage::disk('public')->exists($file->file_path)) {
            Storage::disk('public')->delete($file->file_path);
        }

        $file->delete();

        return response()->json([
            'success' => true,
            'message' => 'File berhasil dihapus'
        ]);
    }

    // Download file (Metode download yang benar di PHP)
    public function download(File $file)
    {
        // Menggunakan path absolut langsung ke public/storage
        $filePath = public_path('storage/' . $file->file_path);

        if (!file_exists($filePath)) {
            return response()->json([
                'success' => false,
                'message' => 'File fisik tidak ditemukan di server'
            ], 404);
        }

        return response()->download($filePath, $file->original_filename);
    }
}