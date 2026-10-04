<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Department;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    // Menampilkan semua daftar departemen (Bisa diakses Admin & Viewer)
    public function index()
    {
        $departments = Department::all();
        return response()->json([
            'success' => true,
            'data' => $departments
        ]);
    }

    // Menambah departemen baru (Khusus Administrator)
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:departments,name|max:255',
        ]);

        $department = Department::create([
            'name' => $request->name,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Departemen berhasil ditambahkan',
            'data' => $department
        ], 201);
    }

    // Menampilkan detail departemen
    public function show(Department $department)
    {
        return response()->json([
            'success' => true,
            'data' => $department
        ]);
    }

    // Mengubah data departemen (Khusus Administrator)
    public function update(Request $request, Department $department)
    {
        $request->validate([
            'name' => 'required|string|unique:departments,name,' . $department->id . '|max:255',
        ]);

        $department->update([
            'name' => $request->name,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Departemen berhasil diperbarui',
            'data' => $department
        ]);
    }

    // Menghapus departemen (Khusus Administrator)
    public function destroy(Department $department)
    {
        $department->delete();

        return response()->json([
            'success' => true,
            'message' => 'Departemen berhasil dihapus'
        ]);
    }
}