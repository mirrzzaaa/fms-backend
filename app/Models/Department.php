<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang terhubung dengan model ini.
     * (Opsional, Laravel otomatis mengenali tabel 'departments' dari nama model).
     * 
     * @var string
     */
    protected $table = 'departments';

    /**
     * Atribut yang dapat diisi secara massal (mass assignable).
     * 
     * @var list<string>
     */
    protected $fillable = [
        'name',
    ];
}