<!DOCTYPE html>
<html lang="id" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>AI Workspace — AI Boss</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.bunny.net/css?family=space-grotesk:600,700|inter:400,500,600&display=swap" rel="stylesheet" />
    <style>
        body { background-color: #0B1120; color: #F8FAFC; font-family: 'Inter', sans-serif; }
        h1, h2, h3, h4 { font-family: 'Space Grotesk', sans-serif; }
        .glass-panel { background: #131B2C; border: 1px solid rgba(255,255,255,0.05); border-radius: 16px; box-shadow: 0 4px 20px rgba(0,0,0,0.3); }
        .input-field { background: #0B1120; border: 1px solid rgba(255,255,255,0.1); color: white; border-radius: 10px; width: 100%; padding: 12px 16px; outline: none; transition: 0.2s; }
        .input-field:focus { border-color: #8B5CF6; box-shadow: 0 0 0 3px rgba(139,92,246,0.2); }
        .btn-gradient { background: linear-gradient(to right, #7C3AED, #8B5CF6); color: white; font-weight: 600; border-radius: 10px; padding: 12px; width: 100%; transition: 0.3s; }
        .btn-gradient:hover { opacity: 0.9; transform: translateY(-1px); }

        /* Animasi Loading Kedip */
        .pulse-loading { animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite; }
        @keyframes pulse { 0%, 100% { opacity: 1; } 50% { opacity: .3; } }
    </style>
</head>
<body class="antialiased min-h-screen flex flex-col">

    <!-- NAVBAR -->
    <nav class="w-full bg-[#0B1120] border-b border-white/5">
        <div class="max-w-7xl mx-auto px-6 h-20 flex justify-between items-center">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-violet-500 to-purple-600 flex items-center justify-center text-xl">🧠</div>
                <h1 class="font-bold text-xl tracking-tight">AI Boss<span class="text-violet-500">.</span></h1>
            </div>
            <a href="{{ url('/dashboard') }}" class="text-sm font-medium text-slate-400 hover:text-white transition">Kembali ke Dashboard</a>
        </div>
    </nav>

    <!-- HEADER TEXT -->
    <div class="max-w-7xl mx-auto px-6 pt-10 pb-6 w-full">
        <h2 class="text-3xl font-bold mb-2">🧠 AI Workspace</h2>
        <p class="text-slate-400 text-sm">Asisten pintar tanpa batas. Tanyakan ide, strategi, atau suruh AI menulis apa saja.</p>
    </div>

    <!-- MAIN CONTENT GRID -->
    <main class="max-w-7xl mx-auto px-6 pb-20 w-full grid grid-cols-1 lg:grid-cols-12 gap-8 flex-1">

        <!-- KOLOM KIRI: INPUT PROMPT -->
        <div class="lg:col-span-5">
            <div class="glass-panel p-6 sticky top-6">
                <h3 class="text-lg font-bold mb-5 flex items-center gap-2">
                    <span class="text-violet-400">💬</span> Tanya AI Boss
                </h3>

                <form id="chatForm" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-semibold text-slate-400 mb-2 uppercase tracking-wider">Instruksi Anda (Prompt)</label>
                        <textarea name="prompt" rows="8" class="input-field resize-none text-sm" placeholder="Contoh: Buatkan saya 3 ide nama brand untuk bisnis kopi susu kekinian yang target pasarnya mahasiswa, beserta filosofinya..." required></textarea>
                    </div>

                    <button type="submit" id="submitBtn" class="btn-gradient mt-2 shadow-lg shadow-violet-500/20">
                        KIRIM KE AI
                    </button>

                    <div id="statusMessage" class="hidden text-xs text-center mt-4 text-red-400 font-medium bg-red-500/10 p-3 rounded-lg border border-red-500/20"></div>
                </form>

                <div class="mt-8 p-4 bg-violet-500/10 border border-violet-500/20 rounded-xl">
                    <h4 class="text-xs font-bold text-violet-400 mb-2">💡 Tips Prompt:</h4>
                    <ul class="text-[11px] text-slate-400 space-y-1 list-disc pl-4">
                        <li>Gunakan kata <b>"Bertindaklah sebagai..."</b> agar AI fokus (misal: Bertindaklah sebagai pengacara bisnis).</li>
                        <li>Berikan konteks yang jelas dan detail.</li>
                        <li>Tentukan format yang Anda inginkan (misal: buat dalam 5 poin).</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- KOLOM KANAN: OUTPUT AI -->
        <div class="lg:col-span-7">
            <div class="glass-panel p-6 h-full flex flex-col min-h-[500px]">

                <div class="flex justify-between items-center mb-6 pb-4 border-b border-white/5">
                    <h3 class="text-lg font-bold flex items-center gap-2">
                        ✨ Hasil AI
                    </h3>
                    <button id="copyBtn" onclick="copyResult()" class="hidden bg-white/5 hover:bg-violet-500 text-slate-300 hover:text-white text-xs font-semibold px-4 py-2 rounded-lg transition">
                        Copy Hasil
                    </button>
                </div>

                <!-- STATE KOSONG -->
                <div id="emptyState" class="flex-1 flex flex-col items-center justify-center text-center opacity-50 py-20">
                    <div class="text-6xl mb-4">🤖</div>
                    <p class="text-slate-400 text-sm max-w-sm">Ruang kerja masih kosong.<br>Ketikkan instruksi Anda di sebelah kiri dan AI akan menampilkannya di sini.</p>
                </div>

                <!-- STATE LOADING -->
                <div id="loadingState" class="hidden flex-1 flex flex-col items-center justify-center text-center py-20">
                    <div class="text-6xl mb-4 pulse-loading">⏳</div>
                    <p class="text-violet-400 font-semibold pulse-loading">AI Sedang Berpikir...</p>
                </div>

                <!-- STATE HASIL (OUTPUT) -->
                <div id="resultState" class="hidden flex-1 overflow-y-auto">
                    <div class="bg-[#0B1120] border border-white/5 rounded-xl p-6 text-[14px] text-slate-300 leading-relaxed shadow-inner" id="aiOutput">
                        <!-- Teks AI akan muncul di sini -->
                    </div>
                </div>

            </div>
        </div>

    </main>

    <script>
        function copyResult() {
            const text = document.getElementById('aiOutput').innerText;
            const btn = document.getElementById('copyBtn');
            navigator.clipboard.writeText(text).then(() => {
                const ori = btn.innerHTML;
                btn.innerHTML = 'Berhasil Dicopy! ✔️';
                btn.classList.replace('bg-white/5', 'bg-emerald-500');
                btn.classList.replace('hover:bg-violet-500', 'hover:bg-emerald-600');
                btn.classList.replace('text-slate-300', 'text-white');
                setTimeout(() => {
                    btn.innerHTML = ori;
                    btn.classList.replace('bg-emerald-500', 'bg-white/5');
                    btn.classList.replace('hover:bg-emerald-600', 'hover:bg-violet-500');
                    btn.classList.replace('text-white', 'text-slate-300');
                }, 2000);
            });
        }

        document.getElementById('chatForm').addEventListener('submit', async function(e) {
            e.preventDefault();

            const btn = document.getElementById('submitBtn');
            const statusDiv = document.getElementById('statusMessage');
            const originalText = btn.innerHTML;

            // Atur UI State
            btn.innerHTML = '✨ MEMPROSES...';
            btn.classList.add('opacity-50', 'cursor-not-allowed');
            statusDiv.classList.add('hidden');

            document.getElementById('emptyState').classList.add('hidden');
            document.getElementById('resultState').classList.add('hidden');
            document.getElementById('copyBtn').classList.add('hidden');
            document.getElementById('loadingState').classList.remove('hidden');

            try {
                const response = await fetch('{{ route("ai-workspace.chat") }}', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: JSON.stringify({
                        prompt: document.querySelector('textarea[name="prompt"]').value
                    })
                });

                const result = await response.json();

                if(!result.success) throw new Error(result.message || 'AI Gagal memproses');

                // Tampilkan Hasil
                document.getElementById('aiOutput').innerHTML = result.hasil_ai;

                document.getElementById('loadingState').classList.add('hidden');
                document.getElementById('resultState').classList.remove('hidden');
                document.getElementById('copyBtn').classList.remove('hidden');

            } catch (err) {
                // Tampilkan Error
                statusDiv.innerText = "❌ Error: " + err.message;
                statusDiv.classList.remove('hidden');

                document.getElementById('loadingState').classList.add('hidden');
                document.getElementById('emptyState').classList.remove('hidden');
            } finally {
                // Kembalikan Tombol
                btn.innerHTML = originalText;
                btn.classList.remove('opacity-50', 'cursor-not-allowed');
            }
        });
    </script>
</body>
</html>
