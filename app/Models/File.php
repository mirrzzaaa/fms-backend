<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage; // <-- 1. Import facade Storage

class File extends Model
{
    use HasFactory;

    protected $fillable = [
        'folder_id',
        'department_id',
        'user_id',
        'title',
        'original_filename',
        'file_path',
        'file_size',
    ];

    // 2. Tambahkan appends agar atribut 'url' otomatis disertakan dalam JSON response
    protected $appends = ['url'];

    // 3. Buat accessor untuk menghasilkan URL publik file
    public function getUrlAttribute()
    {
        if (!$this->file_path) {
            return null;
        }

        return asset('storage/' . $this->file_path);
    }

    /**
     * Relasi ke Folder tempat file berada
     */
    public function folder()
    {
        return $this->belongsTo(Folder::class);
    }

    /**
     * Relasi ke Departemen file
     */
    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    /**
     * Relasi ke User yang mengunggah
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}