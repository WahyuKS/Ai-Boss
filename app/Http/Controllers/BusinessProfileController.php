<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BusinessProfile;
use Illuminate\Support\Facades\Auth;

class BusinessProfileController extends Controller
{
    public function create()
    {
        // Cek apakah user sudah punya profil bisnis
        // Jika sudah punya, lemparkan langsung ke Dashboard
        if (Auth::user()->businessProfile) {
            return redirect()->route('dashboard');
        }

        // Jika belum, tampilkan form pengisian
        return view('profile.business-create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'brand_name' => 'required|string|max:255',
            'primary_platform' => 'required|string',
            'product_category' => 'required|string',
        ]);

        BusinessProfile::create([
            'user_id' => Auth::id(),
            'brand_name' => $request->brand_name,
            'primary_platform' => $request->primary_platform,
            'product_category' => $request->product_category,
        ]);

        return redirect()->route('dashboard');
    }
}
