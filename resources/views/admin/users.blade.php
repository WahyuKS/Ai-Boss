<!DOCTYPE html>
<html lang="id" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Command Center — AI Boss</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.bunny.net/css?family=space-grotesk:600,700|inter:400,500,600|fira-code:400,500&display=swap" rel="stylesheet" />
    <style>
        body { background-color: #020617; color: #F8FAFC; font-family: 'Inter', sans-serif; overflow-x: hidden; }
        h1, h2, h3, h4 { font-family: 'Space Grotesk', sans-serif; }
        .font-mono { font-family: 'Fira Code', monospace; }

        /* Cyber Grid Background */
        .bg-cyber {
            background-color: #020617;
            background-image:
                linear-gradient(rgba(79, 70, 229, 0.05) 1px, transparent 1px),
                linear-gradient(90deg, rgba(79, 70, 229, 0.05) 1px, transparent 1px);
            background-size: 30px 30px;
        }

        /* Laser Scanner Animation */
        @keyframes scan {
            0% { transform: translateY(-100vh); opacity: 0; }
            10% { opacity: 1; }
            90% { opacity: 1; }
            100% { transform: translateY(100vh); opacity: 0; }
        }
        .scanner-line {
            width: 100%; height: 2px;
            background: rgba(79, 70, 229, 0.6);
            box-shadow: 0 0 15px rgba(79, 70, 229, 0.8), 0 0 30px rgba(79, 70, 229, 0.6);
            position: fixed; top: 0; left: 0;
            animation: scan 4s linear infinite;
            pointer-events: none; z-index: 9999;
        }

        /* 3D Cards */
        .card-3d {
            background: linear-gradient(145deg, #0f172a, #1e293b);
            border: 1px solid rgba(255, 255, 255, 0.05);
            box-shadow: 0 10px 30px -10px rgba(0, 0, 0, 0.8), inset 0 1px 0 rgba(255, 255, 255, 0.1);
            border-radius: 16px;
        }

        /* Tech Input Field */
        .tech-input {
            background: rgba(2, 6, 23, 0.6);
            border: 1px solid rgba(79, 70, 229, 0.3);
            color: #818cf8; border-radius: 8px;
            padding: 10px 16px; outline: none; transition: 0.3s;
            box-shadow: inset 0 2px 4px rgba(0,0,0,0.5);
        }
        .tech-input:focus {
            border-color: #6366f1;
            box-shadow: 0 0 0 2px rgba(99, 102, 241, 0.2), inset 0 2px 4px rgba(0,0,0,0.5);
        }

        .row-3d { transition: 0.2s; border-bottom: 1px solid rgba(255,255,255,0.02); }
        .row-3d:hover { background: rgba(30, 41, 59, 0.8); transform: scale(1.002); }
    </style>
</head>
<body class="antialiased min-h-screen bg-cyber flex flex-col relative">

    <!-- Efek Garis Scanner Laser -->
    <div class="scanner-line"></div>

    <nav class="w-full border-b border-indigo-500/20 bg-slate-900/80 backdrop-blur-md sticky top-0 z-40 shadow-[0_4px_20px_rgba(0,0,0,0.5)]">
        <div class="max-w-7xl mx-auto px-6 h-20 flex justify-between items-center">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-indigo-600 to-purple-700 flex items-center justify-center text-xl shadow-[0_0_15px_rgba(79,70,229,0.5)] border border-indigo-400/30">🌌</div>
                <div>
                    <h1 class="font-bold text-xl tracking-tight text-white leading-tight">Command Center</h1>
                    <p class="text-[10px] uppercase tracking-widest text-indigo-400 font-bold font-mono">System Active • Master Override</p>
                </div>
            </div>
            <a href="{{ url('/dashboard') }}" class="text-sm font-bold text-slate-400 hover:text-white transition flex items-center gap-2 border border-slate-700 px-4 py-2 rounded-lg bg-slate-800 hover:bg-slate-700">
                Exit Protocol
            </a>
        </div>
    </nav>

    <main class="max-w-[1400px] mx-auto px-6 pt-10 pb-20 w-full flex-1 relative z-10">

        <!-- Top Metrics -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="card-3d p-6 border-l-4 border-l-indigo-500">
                <h3 class="text-slate-400 text-xs font-bold uppercase tracking-wider mb-2">Network Nodes</h3>
                <div class="flex items-end gap-3"><span class="text-3xl font-black text-white">{{ $totalUsers }}</span><span class="text-indigo-400 text-xs font-bold mb-1">Total Akun</span></div>
            </div>
            <div class="card-3d p-6 border-l-4 border-l-emerald-500">
                <h3 class="text-slate-400 text-xs font-bold uppercase tracking-wider mb-2">Active Businesses</h3>
                <div class="flex items-end gap-3"><span class="text-3xl font-black text-white">{{ $totalBisnis }}</span><span class="text-emerald-400 text-xs font-bold mb-1">Terkoneksi</span></div>
            </div>
            <div class="card-3d p-6 border-l-4 border-l-rose-500">
                <h3 class="text-slate-400 text-xs font-bold uppercase tracking-wider mb-2">Security Clearance</h3>
                <div class="flex items-end gap-3"><span class="text-3xl font-black text-white">{{ $totalAdmins }}</span><span class="text-rose-400 text-xs font-bold mb-1">Master Admin</span></div>
            </div>
        </div>

        <!-- SEARCH & FILTER ALGORITHM PANEL -->
        <div class="card-3d p-6 mb-6">
            <form action="{{ route('admin.users') }}" method="GET" class="flex flex-col md:flex-row gap-4 items-end">
                <div class="flex-1 w-full">
                    <label class="block text-[10px] text-indigo-400 font-bold uppercase tracking-wider mb-2 font-mono">>_ QUERY_SEARCH (Name/Email)</label>
                    <input type="text" name="search" value="{{ request('search') }}" class="tech-input w-full" placeholder="Ketik data target...">
                </div>
                <div class="w-full md:w-48">
                    <label class="block text-[10px] text-indigo-400 font-bold uppercase tracking-wider mb-2 font-mono">>_ FILTER_DATE</label>
                    <input type="date" name="date" value="{{ request('date') }}" class="tech-input w-full text-sm">
                </div>
                <div class="flex gap-2 w-full md:w-auto">
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-500 text-white font-bold px-6 py-2.5 rounded-lg shadow-[0_0_15px_rgba(79,70,229,0.4)] transition">
                        EXECUTE
                    </button>
                    @if(request('search') || request('date'))
                        <a href="{{ route('admin.users') }}" class="bg-slate-700 hover:bg-slate-600 text-white font-bold px-4 py-2.5 rounded-lg transition text-center">
                            RESET
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- DATABASE TABLE -->
        <div class="card-3d overflow-visible">
            <div class="px-6 py-5 border-b border-white/5 flex justify-between items-center bg-slate-900/50 rounded-t-16px">
                <h2 class="text-lg font-bold text-white flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span> Database Pelanggan
                </h2>
                <span class="font-mono text-xs text-indigo-400">Menampilkan {{ $users->count() }} Data</span>
            </div>

            <div class="overflow-x-auto p-2">
                <table class="w-full text-left text-sm text-slate-300 whitespace-nowrap">
                    <thead class="text-[10px] uppercase text-slate-500 font-bold tracking-wider font-mono">
                        <tr>
                            <th class="px-4 py-3 border-b border-slate-700">User Identity</th>
                            <th class="px-4 py-3 border-b border-slate-700">Waktu Register & Login</th>
                            <th class="px-4 py-3 border-b border-slate-700">Data Bisnis</th>
                            <th class="px-4 py-3 border-b border-slate-700 text-center">Output</th>
                            <th class="px-4 py-3 border-b border-slate-700 text-center">Status</th>
                            <th class="px-4 py-3 border-b border-slate-700 text-right">Terminal Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                        <tr class="row-3d">
                            <td class="px-4 py-4">
                                <p class="font-bold text-white text-sm">{{ $user->name }}</p>
                                <p class="text-indigo-300 text-xs">{{ $user->email }}</p>
                            </td>

                            <!-- DATA TANGGAL BARU -->
                            <td class="px-4 py-4">
                                <div class="mb-1 text-xs">
                                    <span class="text-slate-500">Reg:</span>
                                    <span class="text-slate-300 font-mono">{{ $user->created_at->format('d M Y, H:i') }}</span>
                                </div>
                                <div class="text-[11px]">
                                    <span class="text-slate-500">Log:</span>
                                    @if($user->last_login_at)
                                        <span class="text-emerald-400 font-mono">{{ \Carbon\Carbon::parse($user->last_login_at)->diffForHumans() }}</span>
                                    @else
                                        <span class="text-amber-500 italic">Belum Login</span>
                                    @endif
                                </div>
                            </td>

                            <td class="px-4 py-4">
                                @if($user->businessProfile)
                                    <p class="font-bold text-white">{{ $user->businessProfile->nama_bisnis }}</p>
                                    <p class="text-[10px] text-indigo-400 uppercase tracking-wide">{{ $user->businessProfile->platform_utama ?? 'Platform' }}</p>
                                @else
                                    <span class="text-slate-600 italic text-xs">Unregistered</span>
                                @endif
                            </td>

                            <td class="px-4 py-4 text-center">
                                <span class="bg-indigo-900/50 text-indigo-300 px-3 py-1 rounded border border-indigo-500/30 font-mono text-xs">
                                    {{ $user->saved_contents_count }}
                                </span>
                            </td>

                            <td class="px-4 py-4 text-center">
                                @if($user->is_admin)
                                    <span class="bg-rose-500/20 text-rose-400 border border-rose-500/50 px-2 py-1 rounded text-[10px] font-bold uppercase shadow-[0_0_10px_rgba(225,29,72,0.3)]">Admin</span>
                                @else
                                    <span class="bg-emerald-500/10 text-emerald-500 border border-emerald-500/30 px-2 py-1 rounded text-[10px] font-bold uppercase">User</span>
                                @endif
                            </td>

                            <td class="px-4 py-4 text-right">
                                <div class="flex justify-end gap-2">
                                    <form action="{{ route('admin.reset-password', $user->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="bg-slate-800 hover:bg-slate-700 text-blue-400 border border-slate-600 px-2.5 py-1.5 rounded text-xs font-bold transition">
                                            [Pass]
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.delete-user', $user->id) }}" method="POST" onsubmit="return confirm('Hapus Permanen?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="bg-slate-800 hover:bg-rose-900 text-rose-500 hover:text-white border border-rose-900 px-2.5 py-1.5 rounded text-xs font-bold transition">
                                            [Del]
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-4 py-10 text-center text-slate-500 font-mono">>> QUERY_RESULT: DATA NOT FOUND <<</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-6 border-t border-white/5 bg-slate-900/50 rounded-b-16px">
                {{ $users->links() }}
            </div>
        </div>

    </main>
</body>
</html>
