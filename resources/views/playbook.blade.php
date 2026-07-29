<!DOCTYPE html>
<html lang="id" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Business Playbook — AI Boss</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.bunny.net/css?family=space-grotesk:600,700|inter:400,500,600&display=swap" rel="stylesheet" />
    <style>
        body { background-color: #0B1120; color: #F8FAFC; font-family: 'Inter', sans-serif; }
        h1, h2, h3, h4 { font-family: 'Space Grotesk', sans-serif; }
        .glass-panel { background: #131B2C; border: 1px solid rgba(255,255,255,0.05); border-radius: 16px; box-shadow: 0 4px 20px rgba(0,0,0,0.3); }
        .input-field { background: #0B1120; border: 1px solid rgba(255,255,255,0.1); color: white; border-radius: 10px; width: 100%; padding: 12px 16px; outline: none; transition: 0.2s; }
        .input-field:focus { border-color: #06B6D4; box-shadow: 0 0 0 3px rgba(6,182,212,0.2); }
        .btn-gradient { background: linear-gradient(to right, #0891B2, #06B6D4); color: white; font-weight: 600; border-radius: 10px; padding: 12px; width: 100%; transition: 0.3s; }
        .btn-gradient:hover { opacity: 0.9; transform: translateY(-1px); }
    </style>
</head>
<body class="antialiased min-h-screen flex flex-col">

    <!-- NAVBAR -->
    <nav class="w-full bg-[#0B1120] border-b border-white/5">
        <div class="max-w-7xl mx-auto px-6 h-20 flex justify-between items-center">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-cyan-500 to-blue-500 flex items-center justify-center text-xl">📋</div>
                <h1 class="font-bold text-xl tracking-tight">AI Boss<span class="text-cyan-500">.</span></h1>
            </div>
            <a href="{{ url('/dashboard') }}" class="text-sm font-medium text-slate-400 hover:text-white transition">Kembali ke Dashboard</a>
        </div>
    </nav>

    <!-- HEADER TEXT -->
    <div class="max-w-7xl mx-auto px-6 pt-10 pb-6 w-full">
        <h2 class="text-3xl font-bold mb-2">📋 Business Playbook</h2>
        <p class="text-slate-400 text-sm">Pusat dokumentasi SOP untuk melatih karyawan dan mengotomatiskan operasional bisnis.</p>
    </div>

    <!-- MAIN CONTENT GRID -->
    <main class="max-w-7xl mx-auto px-6 pb-20 w-full grid grid-cols-1 lg:grid-cols-12 gap-8 flex-1">

        <!-- KOLOM KIRI: FORM -->
        <div class="lg:col-span-4">
            <div class="glass-panel p-6 sticky top-6">
                <h3 class="text-lg font-bold mb-5 flex items-center gap-2">
                    <span class="text-cyan-400">✨</span> Buat SOP Baru
                </h3>

                <form id="templateForm" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-semibold text-slate-400 mb-1.5 uppercase tracking-wider">Posisi Pekerjaan</label>
                        <input type="text" name="posisi_pekerjaan" class="input-field" placeholder="Contoh: Admin TikTok, Kasir, CS" required>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-400 mb-1.5 uppercase tracking-wider">Tugas / Prosedur Utama</label>
                        <textarea name="tugas_utama" rows="5" class="input-field resize-none text-sm" placeholder="Contoh: Cara membalas komplain pelanggan yang marah, atau Cara closing orderan via WhatsApp." required></textarea>
                    </div>

                    <button type="submit" id="submitBtn" class="btn-gradient mt-4 shadow-lg shadow-cyan-500/20">
                        GENERATE SOP & SIMPAN
                    </button>

                    <div id="statusMessage" class="hidden text-xs text-center mt-3 text-red-400 font-medium"></div>
                </form>
            </div>
        </div>

        <!-- KOLOM KANAN: LIBRARY & FILTER -->
        <div class="lg:col-span-8">
            <div class="glass-panel p-6 min-h-[500px]">

                <div class="flex flex-col md:flex-row items-start md:items-center justify-between mb-6 pb-4 border-b border-white/5 gap-4">
                    <h3 class="text-lg font-bold flex items-center gap-2 whitespace-nowrap">
                        📚 Direktori Playbook
                    </h3>

                    <!-- BOX SEARCH -->
                    <div class="flex gap-2 w-full md:w-auto">
                        <input type="text" id="searchInput" onkeyup="filterLibrary()" placeholder="Cari SOP atau jabatan..." class="input-field !py-2 !px-3 text-xs w-full md:w-64 border-slate-700">
                    </div>
                </div>

                <div class="space-y-4" id="libraryContainer">
                    @forelse($templates ?? [] as $template)
                        @php
                            $parts = explode('||', $template->title);
                            $posisi = count($parts) > 1 ? $parts[0] : 'General';
                            $tugas = count($parts) > 1 ? $parts[1] : $template->title;
                        @endphp

                        <div class="template-card-item bg-[#0B1120] border border-white/5 rounded-xl p-5 hover:border-cyan-500/30 transition duration-300">
                            <div class="flex justify-between items-start mb-3">
                                <div>
                                    <span class="inline-block bg-cyan-500/10 text-cyan-400 border border-cyan-500/20 text-[10px] font-bold uppercase px-2 py-0.5 rounded mb-1">
                                        JABATAN: {{ $posisi }}
                                    </span>
                                    <h4 class="text-sm font-bold text-white search-title mt-1">{{ $tugas }}</h4>
                                </div>
                                <div class="flex gap-2">
                                    <button onclick="deleteTemplate('{{ $template->id }}')" class="bg-red-500/10 hover:bg-red-500 text-red-400 hover:text-white text-[11px] font-semibold px-3 py-1.5 rounded transition">Hapus</button>
                                    <button onclick="copyText('{{ $template->id }}', this)" class="bg-white/5 hover:bg-cyan-500 text-slate-300 hover:text-white text-[11px] font-semibold px-3 py-1.5 rounded transition">Copy</button>
                                </div>
                            </div>
                            <div class="text-[14px] text-slate-300 leading-relaxed search-content" id="content-{{ $template->id }}">
                                {!! $template->content !!}
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-24 empty-state">
                            <div class="text-4xl mb-3">📭</div>
                            <p class="text-slate-400 text-sm">Belum ada SOP tersimpan.<br>Buat sistem pertamamu agar bisnis berjalan autopilot!</p>
                        </div>
                    @endforelse

                    <div id="noResultMessage" class="hidden text-center py-20">
                        <div class="text-4xl mb-3 opacity-50">🔍</div>
                        <p class="text-slate-400 text-sm">SOP tidak ditemukan.</p>
                    </div>
                </div>

                <!-- PAGINATION -->
                <div class="mt-6 border-t border-white/5 pt-6">
                    {{ $templates->links() }}
                </div>

            </div>
        </div>

    </main>

    <script>
        function filterLibrary() {
            const searchVal = document.getElementById('searchInput').value.toLowerCase();
            const cards = document.querySelectorAll('.template-card-item');
            let visibleCount = 0;

            cards.forEach(card => {
                const cardTitle = card.querySelector('.search-title').innerText.toLowerCase();
                const cardContent = card.querySelector('.search-content').innerText.toLowerCase();
                const cardPosisi = card.querySelector('span').innerText.toLowerCase();

                if (cardTitle.includes(searchVal) || cardContent.includes(searchVal) || cardPosisi.includes(searchVal)) {
                    card.style.display = "block";
                    visibleCount++;
                } else {
                    card.style.display = "none";
                }
            });

            const noResult = document.getElementById('noResultMessage');
            if(visibleCount === 0 && cards.length > 0) noResult.classList.remove('hidden');
            else noResult.classList.add('hidden');
        }

        function copyText(id, btn) {
            const text = document.getElementById('content-' + id).innerText;
            navigator.clipboard.writeText(text).then(() => {
                const ori = btn.innerHTML;
                btn.innerHTML = 'Copied!';
                btn.classList.replace('bg-white/5', 'bg-emerald-500');
                btn.classList.replace('hover:bg-cyan-500', 'hover:bg-emerald-600');
                btn.classList.replace('text-slate-300', 'text-white');
                setTimeout(() => {
                    btn.innerHTML = ori;
                    btn.classList.replace('bg-emerald-500', 'bg-white/5');
                    btn.classList.replace('hover:bg-emerald-600', 'hover:bg-cyan-500');
                    btn.classList.replace('text-white', 'text-slate-300');
                }, 2000);
            });
        }

        async function deleteTemplate(id) {
            if (!confirm('Hapus SOP ini secara permanen?')) return;
            try {
                const response = await fetch(`/playbook/destroy/${id}`, {
                    method: 'DELETE',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                });
                const result = await response.json();
                if (result.success) window.location.reload();
                else alert('❌ Gagal menghapus.');
            } catch (error) {
                alert('Terjadi kesalahan koneksi.');
            }
        }

        document.getElementById('templateForm').addEventListener('submit', async function(e) {
            e.preventDefault();

            const btn = document.getElementById('submitBtn');
            const statusDiv = document.getElementById('statusMessage');
            const originalText = btn.innerHTML;

            btn.innerHTML = '✨ MENYUSUN SISTEM AI...';
            btn.classList.add('opacity-50', 'cursor-not-allowed');
            statusDiv.classList.add('hidden');

            try {
                // 1. Generate AI
                const req1 = await fetch('{{ route("playbook.generate") }}', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: JSON.stringify({
                        posisi_pekerjaan: document.querySelector('input[name="posisi_pekerjaan"]').value,
                        tugas_utama: document.querySelector('textarea[name="tugas_utama"]').value
                    })
                });

                const res1 = await req1.json();
                if(!res1.success) throw new Error(res1.message || 'AI Gagal memproses');

                // 2. Simpan Database
                btn.innerHTML = '💾 MENYIMPAN...';

                const req2 = await fetch('{{ route("playbook.store") }}', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: JSON.stringify({
                        posisi_pekerjaan: document.querySelector('input[name="posisi_pekerjaan"]').value,
                        tugas_utama: document.querySelector('textarea[name="tugas_utama"]').value,
                        hasil_ai: res1.hasil_ai
                    })
                });

                const res2 = await req2.json();
                if(!res2.success) throw new Error(res2.message || 'Gagal menyimpan ke database');

                window.location.reload();

            } catch (err) {
                statusDiv.innerText = "❌ Error: " + err.message;
                statusDiv.classList.remove('hidden');
                btn.innerHTML = originalText;
                btn.classList.remove('opacity-50', 'cursor-not-allowed');
            }
        });
    </script>
</body>
</html>
