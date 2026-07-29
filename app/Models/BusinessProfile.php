<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BusinessProfile extends Model
{
    use HasFactory;

    /**
     * Kolom-kolom yang diizinkan untuk diisi data (Mass Assignment)
     * Kolom ini harus sama persis dengan yang ada di file migration
     */
    protected $fillable = [
        'user_id',
        'brand_name',
        'primary_platform',
        'product_category',
        'target_market',
        'platform_utama'
    ];

    /**
     * Relasi balik ke User
     * Ini memberitahu sistem bahwa Profil Bisnis ini adalah milik seorang User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
