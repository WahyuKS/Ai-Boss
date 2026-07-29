<!DOCTYPE html>
<html lang="id" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Lengkapi Profil Bisnis — AI Boss</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.bunny.net/css?family=space-grotesk:400,600,700|inter:400,500,600&display=swap" rel="stylesheet" />
    <style>
        body { background-color: #090D17; color: #F7F9FC; font-family: 'Inter', sans-serif; }
        h1, h2, h3 { font-family: 'Space Grotesk', sans-serif; }
        .bg-grid { position: fixed; inset: 0; z-index: -1; background-image: linear-gradient(rgba(255,255,255,0.02) 1px, transparent 1px), linear-gradient(90deg, rgba(255,255,255,0.02) 1px, transparent 1px); background-size: 40px 40px; mask-image: radial-gradient(ellipse 80% 80% at 50% 0%, black 20%, transparent 80%); }
        .premium-card { background: linear-gradient(145deg, rgba(17, 24, 39, 0.8), rgba(11, 17, 32, 0.8)); border: 1px solid rgba(255,255,255,0.08); border-radius: 24px; box-shadow: 0 20px 40px -10px rgba(0,0,0,0.7); backdrop-filter: blur(12px); }
        .form-input { width: 100%; padding: 16px 20px; border-radius: 12px; background: rgba(0,0,0,0.2); border: 1px solid rgba(255,255,255,0.08); color: #F7F9FC; outline: none; transition: 0.3s; font-size: 15px; }
        .form-input:focus { border-color: #6366F1; box-shadow: 0 0 0 4px rgba(99,102,241,0.15); }
        .form-input option { background: #0F172A; }
        .btn-primary { background: linear-gradient(120deg, #3B82F6 0%, #8B5CF6 100%); box-shadow: 0 1px 0 rgba(255,255,255,0.2) inset, 0 8px 24px -8px rgba(59,130,246,0.5); transition: 0.3s; border: none; cursor: pointer; }
        .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 10px 25px -8px rgba(59,130,246,0.6); }

        /* Floating orbs for background ambiance */
        .orb-1 { position: absolute; top: -10%; left: -10%; width: 300px; height: 300px; background: radial-gradient(circle, rgba(99,102,241,0.15) 0%, transparent 70%); border-radius: 50%; z-index: -1; }
        .orb-2 { position: absolute; bottom: -10%; right: -10%; width: 300px; height: 300px; background: radial-gradient(circle, rgba(139,92,246,0.15) 0%, transparent 70%); border-radius: 50%; z-index: -1; }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4 relative overflow-hidden">

    <div class="bg-grid"></div>
    <div class="orb-1"></div>
    <div class="orb-2"></div>

    <div class="w-full max-w-md">
        <!-- Logo Header -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-gradient-to-br from-indigo-500 to-purple-600 shadow-lg shadow-indigo-500/30 mb-4 text-3xl">🤖</div>
            <h1 class="font-bold text-2xl text-white tracking-tight">AI Boss<span class="text-indigo-500">.</span></h1>
            <p class="text-sm text-slate-400 mt-2">Sistem operasi bisnis cerdas Anda.</p>
        </div>

        <!-- Form Card -->
        <div class="premium-card p-8 relative z-10">
            <div class="mb-8 text-center">
                <h2 class="text-xl font-bold text-white mb-1">Lengkapi Profil Bisnis</h2>
                <p class="text-[13px] text-slate-400">Mari sesuaikan AI dengan kebutuhan bisnis Anda.</p>
            </div>

            <!-- KOTAK PENDETEKSI ERROR -->
            @if ($errors->any())
                <div class="mb-6 p-4 rounded-xl bg-red-500/10 border border-red-500/20 text-red-400 text-sm">
                    <ul class="list-disc pl-5 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Form input sudah menggunakan nama atribut yang tepat (sesuai Controller) -->
            <form method="POST" action="{{ route('business.store') }}" class="space-y-5">
                @csrf

                <div>
                    <label class="block text-[13px] text-slate-400 mb-2 font-medium">Nama Brand / Toko Anda</label>
                    <input type="text" name="brand_name" class="form-input" placeholder="Contoh: Soyyummy" required>
                </div>

                <div>
                    <label class="block text-[13px] text-slate-400 mb-2 font-medium">Platform Utama</label>
                    <select name="primary_platform" class="form-input" required>
                        <option value="TikTok Shop">TikTok Shop</option>
                        <option value="Shopee">Shopee</option>
                        <option value="Tokopedia">Tokopedia</option>
                        <option value="Instagram">Instagram</option>
                        <option value="Lainnya">Lainnya</option>
                    </select>
                </div>

                <div>
                    <label class="block text-[13px] text-slate-400 mb-2 font-medium">Kategori Produk</label>
                    <input type="text" name="product_category" class="form-input" placeholder="Contoh: F&B, Skincare, Fashion" required>
                </div>

                <button type="submit" class="btn-primary w-full py-4 rounded-xl font-bold text-sm tracking-wider uppercase mt-6 flex items-center justify-center gap-2">
                    Simpan & Masuk Dashboard
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                </button>
            </form>
        </div>

        <p class="text-center text-[11px] text-slate-500 mt-6">
            Data ini digunakan untuk melatih model AI khusus untuk bisnis Anda.
        </p>
    </div>

</body>
</html>
