<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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