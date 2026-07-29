<!DOCTYPE html>
<html lang="id" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login Master Admin — AI Boss</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.bunny.net/css?family=space-grotesk:600,700|inter:400,500,600&display=swap" rel="stylesheet" />
    <style>
        body { background-color: #030409; color: #F8FAFC; font-family: 'Inter', sans-serif; }
        .glass-panel { background: #0B1120; border: 1px solid rgba(255,255,255,0.05); border-radius: 20px; box-shadow: 0 20px 40px rgba(0,0,0,0.5); }
        .input-field { background: #05070E; border: 1px solid rgba(255,255,255,0.1); color: white; border-radius: 10px; width: 100%; padding: 14px; outline: none; transition: 0.2s; }
        .input-field:focus { border-color: #E11D48; box-shadow: 0 0 0 2px rgba(225,29,72,0.2); }
    </style>
</head>
<body class="antialiased min-h-screen flex items-center justify-center relative overflow-hidden">

    <!-- Background Merah Gelap Khas Admin -->
    <div class="absolute w-[600px] h-[600px] bg-red-600/10 rounded-full blur-[100px] -top-20 -right-20 pointer-events-none"></div>

    <div class="w-full max-w-md px-6 relative z-10">
        <div class="text-center mb-8">
            <div class="w-16 h-16 rounded-2xl bg-red-600 flex items-center justify-center text-3xl mx-auto mb-4 shadow-lg shadow-red-600/30">👑</div>
            <h1 class="text-2xl font-bold font-['Space_Grotesk'] text-white">Master Admin Portal</h1>
            <p class="text-slate-400 text-sm mt-2">Area terbatas. Hanya untuk pemilik sistem.</p>
        </div>

        <div class="glass-panel p-8">

            @if(session('error'))
                <div class="bg-red-500/10 border border-red-500/20 text-red-400 text-sm p-3 rounded-lg mb-6 text-center">
                    {{ session('error') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="bg-red-500/10 border border-red-500/20 text-red-400 text-sm p-3 rounded-lg mb-6 text-center">
                    {{ $errors->first() }}
                </div>
            @endif

            <form action="{{ route('admin.login.submit') }}" method="POST" class="space-y-5">
                @csrf
                <div>
                    <label class="block text-xs text-slate-400 uppercase tracking-wider mb-2 font-semibold">Email Master</label>
                    <input type="email" name="email" class="input-field" required placeholder="admin@domain.com">
                </div>

                <div>
                    <label class="block text-xs text-slate-400 uppercase tracking-wider mb-2 font-semibold">Kata Sandi</label>
                    <input type="password" name="password" class="input-field" required placeholder="••••••••">
                </div>

                <button type="submit" class="w-full bg-red-600 hover:bg-red-500 text-white font-bold py-3.5 rounded-xl transition shadow-lg shadow-red-600/30 mt-4">
                    Otorisasi Masuk
                </button>
            </form>
        </div>
    </div>
</body>
</html>
