<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\File;
use App\Models\Folder;
use App\Models\Department;

class DashboardController extends Controller
{
    public function index()
    {
        $totalFolders = Folder::count();
        $totalFiles = File::count();
        $totalDepartments = Department::count();

        // 10 File terbaru beserta relasinya
        $latestFiles = File::with(['folder:id,name', 'department:id,name', 'user:id,name'])
            ->latest()
            ->take(10)
            ->get();

        return response()->json([
            'success' => true,
            'data' => [
                'total_folders' => $totalFolders,
                'total_files' => $totalFiles,
                'total_departments' => $totalDepartments,
                'latest_files' => $latestFiles
            ]
        ]);
    }
}