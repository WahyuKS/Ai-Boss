<!DOCTYPE html>
<html lang="id" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Buat PIN Keamanan — AI Boss</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.bunny.net/css?family=space-grotesk:600,700|inter:400,500,600&display=swap" rel="stylesheet" />
    <style>
        body { background-color: #030409; color: #F8FAFC; font-family: 'Inter', sans-serif; }
        .glass-panel { background: #0B1120; border: 1px solid rgba(255,255,255,0.05); border-radius: 24px; box-shadow: 0 20px 40px rgba(0,0,0,0.5); }
        .pin-input { background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.1); color: white; border-radius: 12px; width: 100%; padding: 16px; text-align: center; font-size: 24px; letter-spacing: 12px; outline: none; transition: 0.3s; font-family: 'Space Grotesk', sans-serif; font-weight: 700;}
        .pin-input:focus { border-color: #4F46E5; box-shadow: 0 0 0 2px rgba(79,70,229,0.2); }
    </style>
</head>
<body class="antialiased min-h-screen flex items-center justify-center relative overflow-hidden">

    <!-- Efek Cahaya Belakang -->
    <div class="absolute w-[500px] h-[500px] bg-indigo-600/10 rounded-full blur-[100px] top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 pointer-events-none"></div>

    <div class="w-full max-w-sm px-6 relative z-10">
        <div class="text-center mb-8">
            <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-3xl mx-auto mb-4 shadow-lg shadow-indigo-600/30">🔐</div>
            <h1 class="text-2xl font-bold font-['Space_Grotesk'] text-white">Buat PIN Keamanan</h1>
            <p class="text-slate-400 text-sm mt-2">Amankan akses ke sistem AI dan laporan keuangan bisnis Anda.</p>
        </div>

        <div class="glass-panel p-8">

            @if ($errors->any())
                <div class="bg-red-500/10 border border-red-500/20 text-red-400 text-sm p-3 rounded-lg mb-6 text-center">
                    {{ $errors->first() }}
                </div>
            @endif

            <form action="{{ route('pin.store') }}" method="POST" class="space-y-6">
                @csrf
                <div>
                    <label class="block text-xs text-slate-400 uppercase tracking-wider mb-2 font-semibold text-center">Masukkan 6 Digit PIN</label>
                    <input type="password" name="pin" class="pin-input" required maxlength="6" pattern="\d{6}" placeholder="••••••" inputmode="numeric">
                </div>

                <div>
                    <label class="block text-xs text-slate-400 uppercase tracking-wider mb-2 font-semibold text-center">Ulangi PIN</label>
                    <input type="password" name="pin_confirmation" class="pin-input" required maxlength="6" pattern="\d{6}" placeholder="••••••" inputmode="numeric">
                </div>

                <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-500 text-white font-bold py-4 rounded-xl transition shadow-lg shadow-indigo-600/30">
                    Simpan & Lanjutkan
                </button>
            </form>
        </div>
    </div>
</body>
</html>
