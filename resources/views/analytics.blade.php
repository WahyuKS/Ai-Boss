<!DOCTYPE html>
<html lang="id" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Analytics & Reports — AI Boss</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.bunny.net/css?family=space-grotesk:600,700|inter:400,500,600&display=swap" rel="stylesheet" />
    <!-- Load Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body { background-color: #0B1120; color: #F8FAFC; font-family: 'Inter', sans-serif; }
        h1, h2, h3, h4 { font-family: 'Space Grotesk', sans-serif; }
        .glass-panel { background: #131B2C; border: 1px solid rgba(255,255,255,0.05); border-radius: 16px; box-shadow: 0 4px 20px rgba(0,0,0,0.3); }
        .stat-card { background: linear-gradient(145deg, rgba(30,41,59,0.5), rgba(15,23,42,0.8)); border: 1px solid rgba(255,255,255,0.05); border-radius: 16px; padding: 24px; transition: 0.3s; }
        .stat-card:hover { transform: translateY(-3px); border-color: rgba(99,102,241,0.3); box-shadow: 0 10px 25px rgba(99,102,241,0.1); }
    </style>
</head>
<body class="antialiased min-h-screen flex flex-col">

    <!-- NAVBAR -->
    <nav class="w-full bg-[#0B1120] border-b border-white/5">
        <div class="max-w-7xl mx-auto px-6 h-20 flex justify-between items-center">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-xl">📊</div>
                <h1 class="font-bold text-xl tracking-tight">AI Boss<span class="text-blue-500">.</span></h1>
            </div>
            <a href="{{ url('/dashboard') }}" class="text-sm font-medium text-slate-400 hover:text-white transition">Kembali ke Dashboard</a>
        </div>
    </nav>

    <!-- HEADER TEXT -->
    <div class="max-w-7xl mx-auto px-6 pt-10 pb-6 w-full">
        <h2 class="text-3xl font-bold mb-2">📊 Analytics & Reports</h2>
        <p class="text-slate-400 text-sm">Pantau produktivitas Anda dan lihat seberapa banyak AI telah menghemat waktu bisnis Anda.</p>
    </div>

    <!-- MAIN CONTENT -->
    <main class="max-w-7xl mx-auto px-6 pb-20 w-full flex-1 space-y-8">

        <!-- TOP STATS ROW -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Stat 1: Total AI Generation -->
            <div class="stat-card">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Total Output AI</p>
                        <h3 class="text-4xl font-bold text-white">{{ $totalGenerations }}</h3>
                    </div>
                    <div class="w-10 h-10 rounded-full bg-blue-500/20 flex items-center justify-center text-blue-400 text-xl">
                        🤖
                    </div>
                </div>
                <p class="text-xs text-slate-500">Jumlah data yang di-generate AI</p>
            </div>

            <!-- Stat 2: Tasks Completed -->
            <div class="stat-card">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Tugas Selesai</p>
                        <h3 class="text-4xl font-bold text-white">{{ $completedTasks }} <span class="text-lg text-slate-500 font-medium">/ {{ $totalTasks }}</span></h3>
                    </div>
                    <div class="w-10 h-10 rounded-full bg-emerald-500/20 flex items-center justify-center text-emerald-400 text-xl">
                        ✅
                    </div>
                </div>
                <div class="w-full bg-slate-800 rounded-full h-1.5 mt-4">
                    <div class="bg-emerald-500 h-1.5 rounded-full" style="width: {{ $taskProgress }}%"></div>
                </div>
                <p class="text-[10px] text-slate-400 mt-2 text-right">{{ $taskProgress }}% Selesai</p>
            </div>

            <!-- Stat 3: Estimated Time Saved -->
            <div class="stat-card">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Waktu Dihemat</p>
                        <!-- Asumsi 1 Output AI menghemat 30 menit kerja manusia -->
                        <h3 class="text-4xl font-bold text-white">{{ floor(($totalGenerations * 30) / 60) }} <span class="text-lg text-slate-500 font-medium">Jam</span></h3>
                    </div>
                    <div class="w-10 h-10 rounded-full bg-amber-500/20 flex items-center justify-center text-amber-400 text-xl">
                        ⏱️
                    </div>
                </div>
                <p class="text-xs text-slate-500">Estimasi waktu kerja manual yang dipangkas</p>
            </div>
        </div>

        <!-- CHART SECTION -->
        <div class="glass-panel p-6">
            <div class="mb-6 border-b border-white/5 pb-4">
                <h3 class="text-lg font-bold flex items-center gap-2">
                    📈 Penggunaan Modul AI
                </h3>
                <p class="text-xs text-slate-400 mt-1">Distribusi seberapa sering setiap fitur AI digunakan.</p>
            </div>

            <div class="relative w-full h-[400px]">
                <canvas id="moduleChart"></canvas>
            </div>
        </div>

    </main>

    <!-- CHART.JS INITIALIZATION -->
    <script>
        const ctx = document.getElementById('moduleChart').getContext('2d');

        // Data dari Controller
        const labels = {!! json_encode($labels) !!};
        const dataValues = {!! json_encode($data) !!};

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Jumlah Generate',
                    data: dataValues,
                    backgroundColor: [
                        'rgba(99, 102, 241, 0.7)',  // Indigo (CS)
                        'rgba(236, 72, 153, 0.7)',  // Pink (Content)
                        'rgba(249, 115, 22, 0.7)',  // Orange (Live Script)
                        'rgba(6, 182, 212, 0.7)',   // Cyan (Playbook)
                        'rgba(16, 185, 129, 0.7)',  // Emerald (Workflow)
                        'rgba(245, 158, 11, 0.7)'   // Amber (Profit)
                    ],
                    borderColor: [
                        'rgba(99, 102, 241, 1)',
                        'rgba(236, 72, 153, 1)',
                        'rgba(249, 115, 22, 1)',
                        'rgba(6, 182, 212, 1)',
                        'rgba(16, 185, 129, 1)',
                        'rgba(245, 158, 11, 1)'
                    ],
                    borderWidth: 1,
                    borderRadius: 6,
                    barPercentage: 0.6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: 'rgba(15, 23, 42, 0.9)',
                        titleColor: '#fff',
                        bodyColor: '#cbd5e1',
                        padding: 12,
                        cornerRadius: 8,
                        displayColors: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: 'rgba(255, 255, 255, 0.05)', drawBorder: false },
                        ticks: { color: '#94a3b8', stepSize: 1 }
                    },
                    x: {
                        grid: { display: false },
                        ticks: { color: '#94a3b8', font: { family: 'Inter', size: 12 } }
                    }
                }
            }
        });
    </script>
</body>
</html>
