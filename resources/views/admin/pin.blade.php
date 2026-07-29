<!DOCTYPE html>
<html lang="id" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Keamanan Ganda Admin — AI Boss</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.bunny.net/css?family=space-grotesk:600,700|inter:400,500,600&display=swap" rel="stylesheet" />
    <style>
        body { background-color: #030409; color: #F8FAFC; font-family: 'Inter', sans-serif; }
        .glass-panel { background: #0B1120; border: 1px solid rgba(255,255,255,0.05); border-radius: 20px; box-shadow: 0 20px 40px rgba(0,0,0,0.5); }
        .pin-input { background: #05070E; border: 1px solid rgba(255,255,255,0.1); color: white; border-radius: 12px; width: 100%; padding: 16px; text-align: center; font-size: 24px; letter-spacing: 16px; outline: none; transition: 0.3s; font-family: 'Space Grotesk', sans-serif; font-weight: 700;}
        .pin-input:focus { border-color: #E11D48; box-shadow: 0 0 0 2px rgba(225,29,72,0.2); }
    </style>
</head>
<body class="antialiased min-h-screen flex items-center justify-center relative overflow-hidden">

    <!-- Efek Merah Gelap (Tanda Area Terbatas) -->
    <div class="absolute w-[600px] h-[600px] bg-red-600/10 rounded-full blur-[100px] pointer-events-none"></div>

    <div class="w-full max-w-sm px-6 relative z-10">
        <div class="text-center mb-8">
            <div class="w-20 h-20 rounded-full bg-gradient-to-br from-red-500 to-rose-700 flex items-center justify-center text-3xl mx-auto mb-4 shadow-lg shadow-red-500/30 border-2 border-white/10">
                🔒
            </div>
            <h1 class="text-2xl font-bold font-['Space_Grotesk'] text-white">Sistem Terkunci</h1>
            <p class="text-slate-400 text-sm mt-2">Masukkan PIN Master untuk mengakses database pelanggan.</p>
        </div>

        <div class="glass-panel p-8">

            @if ($errors->any())
                <div class="bg-red-500/10 border border-red-500/20 text-red-400 text-sm p-3 rounded-lg mb-6 text-center animate-pulse">
                    {{ $errors->first() }}
                </div>
            @endif

            <form action="{{ route('admin.pin.submit') }}" method="POST" class="space-y-6">
                @csrf
                <div>
                    <input type="password" name="pin" class="pin-input" required maxlength="6" pattern="\d{6}" placeholder="••••••" inputmode="numeric" autofocus autocomplete="off">
                </div>

                <button type="submit" class="w-full bg-red-600 hover:bg-red-500 text-white font-bold py-4 rounded-xl transition shadow-lg shadow-red-600/30">
                    Otorisasi Akses
                </button>
            </form>
        </div>
    </div>
</body>
</html>
