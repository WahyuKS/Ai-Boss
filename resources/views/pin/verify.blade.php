<!DOCTYPE html>
<html lang="id" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Verifikasi PIN — AI Boss</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.bunny.net/css?family=space-grotesk:600,700|inter:400,500,600&display=swap" rel="stylesheet" />
    <style>
        body { background-color: #030409; color: #F8FAFC; font-family: 'Inter', sans-serif; }
        .glass-panel { background: #0B1120; border: 1px solid rgba(255,255,255,0.05); border-radius: 24px; box-shadow: 0 20px 40px rgba(0,0,0,0.5); }
        .pin-input { background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.1); color: white; border-radius: 12px; width: 100%; padding: 16px; text-align: center; font-size: 24px; letter-spacing: 12px; outline: none; transition: 0.3s; font-family: 'Space Grotesk', sans-serif; font-weight: 700;}
        .pin-input:focus { border-color: #EC4899; box-shadow: 0 0 0 2px rgba(236,72,153,0.2); }
    </style>
</head>
<body class="antialiased min-h-screen flex items-center justify-center relative overflow-hidden">

    <!-- Efek Cahaya Belakang (Pink/Fuchsia untuk verifikasi) -->
    <div class="absolute w-[500px] h-[500px] bg-pink-600/10 rounded-full blur-[100px] top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 pointer-events-none"></div>

    <div class="w-full max-w-sm px-6 relative z-10">
        <div class="text-center mb-8">
            <!-- Menampilkan foto profil atau inisial -->
            <div class="w-20 h-20 rounded-full bg-gradient-to-br from-indigo-500 to-pink-500 flex items-center justify-center text-3xl mx-auto mb-4 shadow-lg shadow-pink-500/20 border-2 border-white/10 font-bold uppercase">
                {{ substr(auth()->user()->name, 0, 1) }}
            </div>
            <h1 class="text-2xl font-bold font-['Space_Grotesk'] text-white">Selamat Datang Kembali</h1>
            <p class="text-slate-400 text-sm mt-2">Masukkan PIN Anda untuk membuka Workspace.</p>
        </div>

        <div class="glass-panel p-8">

            @if ($errors->any())
                <div class="bg-red-500/10 border border-red-500/20 text-red-400 text-sm p-3 rounded-lg mb-6 text-center animate-pulse">
                    {{ $errors->first() }}
                </div>
            @endif

            <form action="{{ route('pin.check') }}" method="POST" class="space-y-6">
                @csrf
                <div>
                    <input type="password" name="pin" class="pin-input" required maxlength="6" pattern="\d{6}" placeholder="••••••" inputmode="numeric" autofocus>
                </div>

                <button type="submit" class="w-full bg-gradient-to-r from-indigo-500 to-pink-500 hover:from-indigo-600 hover:to-pink-600 text-white font-bold py-4 rounded-xl transition shadow-lg shadow-pink-500/30">
                    Buka Kunci
                </button>
            </form>

            <div class="mt-6 text-center">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-xs text-slate-500 hover:text-white transition">Bukan {{ auth()->user()->name }}? Keluar</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
