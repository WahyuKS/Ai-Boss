<!DOCTYPE html>
<html lang="id" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard — AI Boss</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=space-grotesk:400,600,700|inter:400,500,600&display=swap" rel="stylesheet" />

    <!-- Tailwind CSS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --bg: #090D17;
            --surface: rgba(255,255,255,0.03);
            --border: rgba(255,255,255,0.08);
            --gradient: linear-gradient(120deg, #3B82F6 0%, #8B5CF6 100%);
        }
        body {
            background-color: var(--bg);
            color: #F7F9FC;
            font-family: 'Inter', sans-serif;
            overflow-x: hidden;
        }
        h1, h2, h3, h4 {
            font-family: 'Space Grotesk', sans-serif;
        }

        /* Background Grid Ambient */
        .bg-grid {
            position: fixed; inset: 0; z-index: -1;
            background-image: linear-gradient(rgba(255,255,255,0.02) 1px, transparent 1px), linear-gradient(90deg, rgba(255,255,255,0.02) 1px, transparent 1px);
            background-size: 40px 40px;
            mask-image: radial-gradient(ellipse 80% 80% at 50% 0%, black 20%, transparent 80%);
        }

        /* Glassmorphism Navbar */
        .glass-nav {
            background: rgba(9, 13, 23, 0.7);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-bottom: 1px solid var(--border);
        }

        /* Premium Cards */
        .premium-card {
            background: linear-gradient(145deg, rgba(17, 24, 39, 0.8), rgba(11, 17, 32, 0.8));
            border: 1px solid var(--border);
            border-radius: 20px;
            box-shadow: 0 10px 30px -10px rgba(0,0,0,0.5), inset 0 1px 0 rgba(255,255,255,0.05);
            backdrop-filter: blur(12px);
        }

        /* Interactive Buttons */
        .action-btn {
            background: rgba(255,255,255,0.03);
            border: 1px solid var(--border);
            transition: all 0.3s ease;
        }
        .action-btn:hover {
            background: rgba(99, 102, 241, 0.1);
            border-color: rgba(99, 102, 241, 0.4);
            transform: translateY(-2px);
            box-shadow: 0 10px 20px -10px rgba(99, 102, 241, 0.3);
        }

        /* Custom Checkbox */
        .task-checkbox {
            appearance: none; width: 22px; height: 22px;
            border: 2px solid rgba(255,255,255,0.2);
            border-radius: 50%; cursor: pointer;
            transition: all 0.3s ease; position: relative;
        }
        .task-checkbox:checked {
            background: var(--gradient);
            border-color: transparent;
        }
        .task-checkbox:checked::after {
            content: '✓'; position: absolute;
            top: 50%; left: 50%; transform: translate(-50%, -50%);
            color: white; font-size: 14px; font-weight: bold;
        }

        /* Task Item Transition */
        .task-item { transition: all 0.5s ease; }

        /* Loading Overlay */
        .loading-overlay {
            position: absolute; inset: 0; background: rgba(9, 13, 23, 0.8);
            backdrop-filter: blur(4px); z-index: 20; border-radius: 20px;
            display: flex; flex-direction: column; align-items: center; justify-content: center;
            opacity: 0; pointer-events: none; transition: opacity 0.3s ease;
        }
        .loading-overlay.active { opacity: 1; pointer-events: auto; }
        .spinner {
            width: 40px; height: 40px; border: 3px solid rgba(99,102,241,0.2);
            border-top-color: #6366F1; border-radius: 50%;
            animation: spin 1s linear infinite; margin-bottom: 12px;
        }
        @keyframes spin { to { transform: rotate(360deg); } }

        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: #0B0F19; }
        ::-webkit-scrollbar-thumb { background: #1E293B; border-radius: 4px; }

        /* Custom Pagination */
        nav[role="navigation"] { margin-top: 1rem; }
        nav[role="navigation"] p { color: #94a3b8 !important; font-size: 0.875rem; margin-bottom: 1rem; }
        nav[role="navigation"] span, nav[role="navigation"] a, nav[role="navigation"] svg {
            border-color: rgba(255, 255, 255, 0.1) !important;
            background-color: rgba(30, 41, 59, 0.3) !important;
            color: #94a3b8 !important;
        }
        nav[role="navigation"] a:hover {
            background-color: rgba(99, 102, 241, 0.15) !important;
            color: #fff !important;
        }
        nav[role="navigation"] span[aria-current="page"] > span {
            background-color: rgba(99, 102, 241, 0.5) !important;
            color: #fff !important;
            border-color: rgba(99, 102, 241, 0.5) !important;
        }
    </style>
</head>
<body>

    <div class="bg-grid"></div>

    <!-- NAVBAR -->
    <nav class="glass-nav fixed w-full z-50 top-0">
        <div class="max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center shadow-lg shadow-indigo-500/20">
                        <span class="text-white font-bold text-xl">🤖</span>
                    </div>
                    <div>
                        <h1 class="font-bold text-xl tracking-tight text-white leading-tight">AI Boss<span class="text-indigo-500">.</span></h1>
                        <p class="text-[11px] text-slate-400 uppercase tracking-widest font-semibold">Operating System</p>
                    </div>
                </div>

                <div class="flex items-center gap-6">
                    <div class="hidden sm:flex items-center gap-2 bg-slate-800/50 border border-slate-700/50 px-4 py-1.5 rounded-full">
                        <div class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></div>
                        <span class="text-sm font-medium text-slate-300">{{ auth()->user()->businessProfile->brand_name ?? 'Belum diset' }}</span>
                    </div>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-sm font-medium text-slate-400 hover:text-white transition">Keluar</button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <!-- MAIN CONTENT -->
    <main class="pt-32 pb-20">
        <!-- Container dilebarkan menjadi 1400px agar lega -->
        <div class="max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-8">

            <!-- GRID UTAMA (KIRI & KANAN) -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

                <!-- KOLOM KIRI: SIDEBAR MODUL PINTAR (Makan 3 Kolom) -->
                <!-- Memakai sticky agar saat konten dikanan discroll, menu kiri tetap diam -->
                <div class="lg:col-span-3 lg:sticky lg:top-32 order-2 lg:order-1">
                    <h4 class="text-sm font-bold text-slate-500 mb-4 uppercase tracking-wider pl-1">Akses Modul Pintar</h4>

                    <div class="space-y-4">
                        <!-- 1. Content Studio -->
                        <div onclick="window.location.href='{{ route('content-studio') }}'" class="premium-card p-4 cursor-pointer hover:-translate-y-1 transition duration-300 group">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-xl bg-purple-500/10 text-purple-400 flex items-center justify-center text-xl border border-purple-500/20 group-hover:scale-110 transition">📱</div>
                                <div>
                                    <h4 class="font-bold text-slate-200 text-sm">Content Studio</h4>
                                    <p class="text-[11px] text-slate-400 mt-0.5">Generator caption & skrip</p>
                                </div>
                            </div>
                        </div>

                        <!-- 2. Live Script Generator -->
                        <div onclick="window.location.href='{{ route('live-script') }}'" class="premium-card p-4 cursor-pointer hover:-translate-y-1 transition duration-300 group">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-xl bg-pink-500/10 text-pink-400 flex items-center justify-center text-xl border border-pink-500/20 group-hover:scale-110 transition">🎥</div>
                                <div>
                                    <h4 class="font-bold text-slate-200 text-sm">Live Script</h4>
                                    <p class="text-[11px] text-slate-400 mt-0.5">Skrip jualan siap pakai</p>
                                </div>
                            </div>
                        </div>

                        <!-- 3. Profit Studio -->
                        <div onclick="window.location.href='{{ route('profit-studio') }}'" class="premium-card p-4 cursor-pointer hover:-translate-y-1 transition duration-300 group">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-xl bg-emerald-500/10 text-emerald-400 flex items-center justify-center text-xl border border-emerald-500/20 group-hover:scale-110 transition">📊</div>
                                <div>
                                    <h4 class="font-bold text-slate-200 text-sm">Profit Studio</h4>
                                    <p class="text-[11px] text-slate-400 mt-0.5">Kalkulator HPP & margin</p>
                                </div>
                            </div>
                        </div>

                        <!-- 4. CS Center -->
                        <div onclick="window.location.href='{{ route('customer-center') }}'" class="premium-card p-4 cursor-pointer hover:-translate-y-1 transition duration-300 group">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-xl bg-blue-500/10 text-blue-400 flex items-center justify-center text-xl border border-blue-500/20 group-hover:scale-110 transition">🎧</div>
                                <div>
                                    <h4 class="font-bold text-slate-200 text-sm">CS Center</h4>
                                    <p class="text-[11px] text-slate-400 mt-0.5">Template balas otomatis</p>
                                </div>
                            </div>
                        </div>

                        <!-- 5. Business Playbook -->
                        <div onclick="window.location.href='{{ route('playbook') }}'" class="premium-card p-4 cursor-pointer hover:-translate-y-1 transition duration-300 group">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-xl bg-cyan-500/10 text-cyan-400 flex items-center justify-center text-xl border border-cyan-500/20 group-hover:scale-110 transition">📘</div>
                                <div>
                                    <h4 class="font-bold text-slate-200 text-sm">Playbook</h4>
                                    <p class="text-[11px] text-slate-400 mt-0.5">SOP bisnis harian</p>
                                </div>
                            </div>
                        </div>

                        <!-- 6. Analytics -->
                        <div onclick="window.location.href='{{ route('analytics') }}'" class="premium-card p-4 cursor-pointer hover:-translate-y-1 transition duration-300 group">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-xl bg-indigo-500/10 text-indigo-400 flex items-center justify-center text-xl border border-indigo-500/20 group-hover:scale-110 transition">📈</div>
                                <div>
                                    <h4 class="font-bold text-slate-200 text-sm">Analytics</h4>
                                    <p class="text-[11px] text-slate-400 mt-0.5">Insight & performa</p>
                                </div>
                            </div>
                        </div>

                        <!-- 7. Workflow Automation -->
                        <div onclick="window.location.href='{{ route('workflow') }}'" class="premium-card p-4 cursor-pointer hover:-translate-y-1 transition duration-300 group">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-xl bg-teal-500/10 text-teal-400 flex items-center justify-center text-xl border border-teal-500/20 group-hover:scale-110 transition">⚙️</div>
                                <div>
                                    <h4 class="font-bold text-slate-200 text-sm">Workflow</h4>
                                    <p class="text-[11px] text-slate-400 mt-0.5">Otomatisasi kerja</p>
                                </div>
                            </div>
                        </div>

                        <!-- 8. AI Workspace -->
                        <div onclick="window.location.href='{{ route('ai-workspace') }}'" class="premium-card p-4 cursor-pointer hover:-translate-y-1 transition duration-300 group">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-xl bg-yellow-500/10 text-yellow-400 flex items-center justify-center text-xl border border-yellow-500/20 group-hover:scale-110 transition">🧩</div>
                                <div>
                                    <h4 class="font-bold text-slate-200 text-sm">AI Workspace</h4>
                                    <p class="text-[11px] text-slate-400 mt-0.5">Ruang kerja tunggal</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- KOLOM KANAN: KONTEN UTAMA (Makan 9 Kolom) -->
                <div class="lg:col-span-9 space-y-8 order-1 lg:order-2">

                    <!-- HEADER GREETING -->
                    <div>
                        <h2 class="text-3xl font-bold text-white mb-2">Selamat datang, {{ explode(' ', auth()->user()->name)[0] }} 👋</h2>
                        <p class="text-slate-400">Pilih fokus strategi Anda hari ini, dan biarkan AI meracik rencana kerjanya.</p>
                    </div>

                    <!-- Alerts -->
                    @if(isset($errorMessage))
                        <div class="p-4 rounded-xl bg-orange-500/10 border border-orange-500/20 text-orange-400 text-sm font-medium flex items-center gap-3">
                            <span class="text-lg">⏳</span> {{ $errorMessage }}
                        </div>
                    @endif
                    @if(session('success'))
                        <div class="p-4 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-sm font-medium flex items-center gap-3">
                            <span>✓</span> {{ session('success') }}
                        </div>
                    @endif
                    @if(session('error'))
                        <div class="p-4 rounded-xl bg-red-500/10 border border-red-500/20 text-red-400 text-sm font-medium flex items-center gap-3">
                            <span>✕</span> {{ session('error') }}
                        </div>
                    @endif

                    <!-- SECTION 1: WORK MODE (INTENT) -->
                    <div class="premium-card p-6 sm:p-8 relative w-full">
                        <div id="loadingOverlay" class="loading-overlay">
                            <div class="spinner"></div>
                            <p class="text-indigo-400 font-medium text-sm animate-pulse">AI sedang menganalisis strategi...</p>
                        </div>

                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-8 h-8 rounded-lg bg-indigo-500/20 text-indigo-400 flex items-center justify-center border border-indigo-500/30">🎯</div>
                            <h3 class="text-lg font-bold text-white">Mode Fokus Hari Ini</h3>
                        </div>

                        <form method="POST" action="{{ route('aiboss.generate') }}" onsubmit="document.getElementById('loadingOverlay').classList.add('active')">
                            @csrf
                            <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                                <button type="submit" name="intent" value="Fokus mengejar omzet dan membuat promo kilat hari ini" class="action-btn rounded-xl p-4 text-left flex flex-col gap-2">
                                    <span class="text-2xl">📈</span>
                                    <span class="text-sm font-bold text-slate-200">Kejar Omzet</span>
                                </button>
                                <button type="submit" name="intent" value="Fokus membuat ide konten promosi untuk TikTok dan Instagram" class="action-btn rounded-xl p-4 text-left flex flex-col gap-2">
                                    <span class="text-2xl">🎥</span>
                                    <span class="text-sm font-bold text-slate-200">Fokus Konten</span>
                                </button>
                                <button type="submit" name="intent" value="Persiapan melakukan Live Streaming untuk berjualan" class="action-btn rounded-xl p-4 text-left flex flex-col gap-2">
                                    <span class="text-2xl">🛍️</span>
                                    <span class="text-sm font-bold text-slate-200">Persiapan Live</span>
                                </button>
                                <button type="submit" name="intent" value="Fokus membalas chat pelanggan dan melakukan follow-up repeat order" class="action-btn rounded-xl p-4 text-left flex flex-col gap-2">
                                    <span class="text-2xl">💬</span>
                                    <span class="text-sm font-bold text-slate-200">CS & Follow-up</span>
                                </button>
                                <button type="submit" name="intent" value="Merapikan catatan keuangan, menghitung HPP, dan margin laba" class="action-btn rounded-xl p-4 text-left flex flex-col gap-2">
                                    <span class="text-2xl">💰</span>
                                    <span class="text-sm font-bold text-slate-200">Keuangan</span>
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- DAFTAR TUGAS AKTIF & HISTORY -->
                    <div class="space-y-8">
                        <div class="premium-card p-6 sm:p-8 w-full">
                            <div class="flex items-center gap-3 mb-6">
                                <div class="w-8 h-8 rounded-lg bg-orange-500/20 text-orange-400 flex items-center justify-center border border-orange-500/30">⚡</div>
                                <h3 class="text-lg font-bold text-white">Action Plan Anda</h3>
                                <span id="taskCounter" class="ml-auto bg-slate-800 text-xs font-bold px-2.5 py-1 rounded-md text-slate-400 border border-slate-700">{{ $tasks->total() }} Tugas</span>
                            </div>

                            <ul class="space-y-3" id="taskList">
                                @forelse($tasks as $task)
                                    <li id="task-{{ $task->id }}" class="task-item flex items-start gap-4 p-4 rounded-xl bg-slate-900/50 border border-slate-800 hover:border-slate-600 hover:bg-slate-800/50 cursor-pointer">
                                        <input type="checkbox" onchange="completeTask({{ $task->id }})" class="task-checkbox flex-shrink-0 mt-0.5">
                                        <span class="text-slate-300 text-sm leading-relaxed task-title">{{ $task->title }}</span>
                                    </li>
                                @empty
                                    <div id="empty-state" class="text-center py-10 border border-dashed border-slate-700 rounded-xl bg-slate-900/20">
                                        <span class="text-4xl block mb-3 opacity-50">🏝️</span>
                                        <p class="text-slate-400 text-sm">Semua tugas beres. Bisnis Anda dalam kendali penuh.<br>Silakan pilih mode fokus di atas untuk tugas baru.</p>
                                    </div>
                                @endforelse
                            </ul>

                            <div class="mt-6">
                                {{ $tasks->appends(request()->query())->links() }}
                            </div>
                        </div>

                        <!-- RIWAYAT TUGAS -->
                        <div class="w-full">
                            <h4 class="text-sm font-bold text-slate-500 mb-4 uppercase tracking-wider flex items-center gap-2">
                                <span>🕒</span> Riwayat Diselesaikan
                            </h4>
                            <ul class="space-y-2" id="completedTaskList">
                                @forelse($completedTasks as $ctask)
                                    <li class="flex items-center gap-3 p-3 rounded-lg bg-slate-900/30 border border-slate-800/50 text-slate-500 text-sm">
                                        <span class="text-emerald-500/50 font-bold">✓</span>
                                        <span class="line-through flex-1 truncate">{{ $ctask->title }}</span>
                                        <span class="text-xs bg-slate-800 px-2 py-1 rounded text-slate-400">{{ $ctask->updated_at->format('H:i') }}</span>
                                    </li>
                                @empty
                                    <li id="empty-history" class="text-slate-600 text-sm italic p-4 text-center border border-dashed border-slate-800 rounded-lg">Belum ada riwayat hari ini.</li>
                                @endforelse
                            </ul>
                            <div class="mt-4">
                                {{ $completedTasks->appends(request()->query())->links() }}
                            </div>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </main>

    <!-- AJAX SCRIPT -->
    <script>
        function completeTask(taskId) {
            const taskElement = document.getElementById(`task-${taskId}`);
            const taskTitle = taskElement.querySelector('.task-title').innerText;

            taskElement.style.opacity = '0.3';
            taskElement.style.transform = 'translateX(10px)';

            fetch(`/tasks/${taskId}/complete`, {
                method: 'PATCH',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if(data.success) {
                    const counterBadge = document.getElementById('taskCounter');
                    if (counterBadge) {
                        let currentCount = parseInt(counterBadge.innerText);
                        if (!isNaN(currentCount) && currentCount > 0) {
                            counterBadge.innerText = (currentCount - 1) + ' Tugas';
                        }
                    }
                    taskElement.style.height = '0px';
                    taskElement.style.padding = '0px';
                    taskElement.style.margin = '0px';
                    taskElement.style.border = 'none';
                    taskElement.style.overflow = 'hidden';

                    setTimeout(() => {
                        taskElement.remove();
                        checkEmptyState();
                        addToHistory(taskTitle);
                    }, 400);
                }
            })
            .catch(error => {
                alert('Gagal mengupdate tugas. Silakan coba lagi.');
                taskElement.style.opacity = '1';
                taskElement.style.transform = 'none';
                taskElement.querySelector('input').checked = false;
            });
        }

        function addToHistory(title) {
            const historyList = document.getElementById('completedTaskList');
            const emptyHistoryText = document.getElementById('empty-history');
            if (emptyHistoryText) emptyHistoryText.remove();

            const now = new Date();
            const timeString = now.getHours().toString().padStart(2, '0') + ':' + now.getMinutes().toString().padStart(2, '0');

            const newLi = document.createElement('li');
            newLi.className = "flex items-center gap-3 p-3 rounded-lg bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-sm transition-all duration-500 transform -translate-y-2 opacity-0";
            newLi.innerHTML = `
                <span class="font-bold">✓</span>
                <span class="line-through flex-1 truncate">${title}</span>
                <span class="text-[10px] bg-emerald-500/20 px-2 py-1 rounded font-bold uppercase">Baru saja</span>
            `;

            historyList.insertBefore(newLi, historyList.firstChild);

            setTimeout(() => {
                newLi.style.opacity = '1';
                newLi.style.transform = 'none';
            }, 10);

            setTimeout(() => {
                newLi.className = "flex items-center gap-3 p-3 rounded-lg bg-slate-900/30 border border-slate-800/50 text-slate-500 text-sm transition-colors duration-1000";
                newLi.querySelector('.font-bold').className = "text-emerald-500/50 font-bold";
                const timeBadge = newLi.querySelector('.text-\\[10px\\]');
                timeBadge.className = "text-xs bg-slate-800 px-2 py-1 rounded text-slate-400";
                timeBadge.innerText = timeString;
            }, 3000);
        }

        function checkEmptyState() {
            const taskList = document.getElementById('taskList');
            if (taskList.querySelectorAll('li:not(#empty-state)').length === 0) {
                if (!document.getElementById('empty-state')) {
                    taskList.innerHTML = `
                        <div id="empty-state" class="text-center py-10 border border-dashed border-slate-700 rounded-xl bg-slate-900/20 transition-opacity duration-500">
                            <span class="text-4xl block mb-3 opacity-50">🏝️</span>
                            <p class="text-slate-400 text-sm">Semua tugas beres. Bisnis Anda dalam kendali penuh.<br>Silakan pilih mode fokus di atas untuk tugas baru.</p>
                        </div>`;
                }
            }
        }
    </script>
</body>
</html>
