<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Department;
use App\Models\Folder;
use App\Models\File;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class LionFmsSeeder extends Seeder
{
    public function run(): void
    {
        // Pastikan disk public storage siap
        Storage::disk('public')->makeDirectory('uploads/files');

        // 1. Buat Akun Pengguna (Admin & Viewer)
        $admin = User::firstOrCreate(
            ['email' => 'admin@liongroup.co.id'],
            [
                'name' => 'FMS Administrator',
                'password' => Hash::make('LionGroup2026!'),
                'role' => 'admin',
            ]
        );

        $viewer = User::firstOrCreate(
            ['email' => 'viewer@liongroup.co.id'],
            [
                'name' => 'FMS Viewer',
                'password' => Hash::make('LionGroup2026!'),
                'role' => 'viewer',
            ]
        );

        // 2. Buat Departemen
        $deptIt = Department::firstOrCreate(['name' => 'Information Technology (IT)']);
        $deptFinance = Department::firstOrCreate(['name' => 'Keuangan & Akuntansi']);
        $deptHrd = Department::firstOrCreate(['name' => 'HR & General Affairs']);

        // 3. Buat Folder Parent (Utama) untuk Departemen IT (Sertakan user_id)
        $folderItRoot = Folder::firstOrCreate(
            ['name' => 'Dokumen Infrastruktur IT'],
            [
                'parent_id' => null,
                'user_id' => $admin->id, // <-- Ditambahkan agar tidak error not-null constraint
            ]
        );

        // 4. Buat Sub-Folder (Child Folder) di dalam Folder IT Root
        $folderItSub = Folder::firstOrCreate(
            ['name' => 'Jaringan & Server 2026'],
            [
                'parent_id' => $folderItRoot->id,
                'user_id' => $admin->id, // <-- Ditambahkan
            ]
        );

        // 5. Buat Folder Parent untuk Keuangan
        $folderFinanceRoot = Folder::firstOrCreate(
            ['name' => 'Laporan Keuangan'],
            [
                'parent_id' => null,
                'user_id' => $admin->id, // <-- Ditambahkan
            ]
        );

        // 6. Masukkan File Dummy ke dalam Folder Parent (IT Root)
        File::firstOrCreate(
            ['title' => 'Panduan Setup Jaringan Kantor Pusat'],
            [
                'folder_id' => $folderItRoot->id,
                'department_id' => $deptIt->id,
                'user_id' => $admin->id,
                'original_filename' => 'panduan_jaringan.pdf',
                'file_path' => 'uploads/files/dummy_jaringan.pdf',
                'file_size' => 154200,
            ]
        );

        // 7. Masukkan File Dummy ke dalam Sub-Folder
        File::firstOrCreate(
            ['title' => 'Konfigurasi Server Utama Q1'],
            [
                'folder_id' => $folderItSub->id,
                'department_id' => $deptIt->id,
                'user_id' => $admin->id,
                'original_filename' => 'config_server_q1.pdf',
                'file_path' => 'uploads/files/dummy_server.pdf',
                'file_size' => 320100,
            ]
        );

        // 8. Masukkan File Dummy ke dalam Folder Keuangan
        File::firstOrCreate(
            ['title' => 'Laporan Audit Keuangan Q1 2026'],
            [
                'folder_id' => $folderFinanceRoot->id,
                'department_id' => $deptFinance->id,
                'user_id' => $admin->id,
                'original_filename' => 'laporan_keuangan_q1.pdf',
                'file_path' => 'uploads/files/dummy_keuangan.pdf',
                'file_size' => 512000,
            ]
        );
    }
}