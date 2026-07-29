<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SavedContent extends Model
{
    use HasFactory;

    // Mengizinkan kolom-kolom ini diisi data
    protected $fillable = [
        'user_id',
        'module_name',
        'title',
        'content',
    ];

    // Relasi balik ke User (Setiap konten ini adalah milik 1 User)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
