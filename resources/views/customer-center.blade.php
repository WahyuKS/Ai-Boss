<!DOCTYPE html>
<html lang="id" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Customer Center — AI Boss</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.bunny.net/css?family=space-grotesk:600,700|inter:400,500,600&display=swap" rel="stylesheet" />
    <style>
        body { background-color: #0B1120; color: #F8FAFC; font-family: 'Inter', sans-serif; }
        h1, h2, h3, h4 { font-family: 'Space Grotesk', sans-serif; }
        .glass-panel { background: #131B2C; border: 1px solid rgba(255,255,255,0.05); border-radius: 16px; box-shadow: 0 4px 20px rgba(0,0,0,0.3); }
        .input-field { background: #0B1120; border: 1px solid rgba(255,255,255,0.1); color: white; border-radius: 10px; width: 100%; padding: 12px 16px; outline: none; transition: 0.2s; }
        .input-field:focus { border-color: #6366F1; box-shadow: 0 0 0 3px rgba(99,102,241,0.2); }
        .btn-gradient { background: linear-gradient(to right, #4F46E5, #7C3AED); color: white; font-weight: 600; border-radius: 10px; padding: 12px; width: 100%; transition: 0.3s; }
        .btn-gradient:hover { opacity: 0.9; transform: translateY(-1px); }
    </style>
</head>
<body class="antialiased min-h-screen flex flex-col">

    <!-- NAVBAR -->
    <nav class="w-full bg-[#0B1120] border-b border-white/5">
        <div class="max-w-7xl mx-auto px-6 h-20 flex justify-between items-center">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-xl">🤖</div>
                <h1 class="font-bold text-xl tracking-tight">AI Boss<span class="text-indigo-500">.</span></h1>
            </div>
            <a href="{{ url('/dashboard') }}" class="text-sm font-medium text-slate-400 hover:text-white transition">Kembali ke Dashboard</a>
        </div>
    </nav>

    <!-- HEADER TEXT -->
    <div class="max-w-7xl mx-auto px-6 pt-10 pb-6 w-full">
        <h2 class="text-3xl font-bold mb-2">💬 Customer Center</h2>
        <p class="text-slate-400 text-sm">Pusat komando balasan cepat untuk tingkatkan kepuasan pelanggan.</p>
    </div>

    <!-- MAIN CONTENT GRID -->
    <main class="max-w-7xl mx-auto px-6 pb-20 w-full grid grid-cols-1 lg:grid-cols-12 gap-8 flex-1">

        <!-- KOLOM KIRI: FORM -->
        <div class="lg:col-span-4">
            <div class="glass-panel p-6 sticky top-6">
                <h3 class="text-lg font-bold mb-5 flex items-center gap-2">
                    <span class="text-indigo-400">✨</span> Buat Template Baru
                </h3>

                <form id="templateForm" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-semibold text-slate-400 mb-1.5 uppercase tracking-wider">Judul Template</label>
                        <input type="text" name="judul" class="input-field" placeholder="Contoh: Barang Rusak" required>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-400 mb-1.5 uppercase tracking-wider">Kategori</label>
                        <select name="kategori" class="input-field text-sm" required>
                            <option value="Komplain">Komplain</option>
                            <option value="Balasan Chat">Balasan Chat</option>
                            <option value="Follow Up">Follow Up</option>
                            <option value="Promo">Promo</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-400 mb-1.5 uppercase tracking-wider">Isi Pesan (Kasar)</label>
                        <textarea name="isi_pesan" rows="5" class="input-field resize-none text-sm" placeholder="Ketik santai di sini..." required></textarea>
                    </div>

                    <button type="submit" id="submitBtn" class="btn-gradient mt-2 shadow-lg shadow-indigo-500/20">
                        RACIK AI & SIMPAN
                    </button>

                    <div id="statusMessage" class="hidden text-xs text-center mt-3 text-red-400 font-medium"></div>
                </form>
            </div>
        </div>

        <!-- KOLOM KANAN: LIBRARY & FILTER -->
        <div class="lg:col-span-8">
            <div class="glass-panel p-6 min-h-[500px]">

                <!-- HEADER & PENCARIAN -->
                <div class="flex flex-col md:flex-row items-start md:items-center justify-between mb-6 pb-4 border-b border-white/5 gap-4">
                    <h3 class="text-lg font-bold flex items-center gap-2 whitespace-nowrap">
                        📚 Library Tersimpan
                    </h3>

                    <!-- BOX FILTER & SEARCH -->
                    <div class="flex gap-2 w-full md:w-auto">
                        <select id="filterKategori" onchange="filterLibrary()" class="input-field !py-2 !px-3 text-xs w-full md:w-40 border-slate-700">
                            <option value="">Semua Kategori</option>
                            <option value="komplain">Komplain</option>
                            <option value="balasan chat">Balasan Chat</option>
                            <option value="follow up">Follow Up</option>
                            <option value="promo">Promo</option>
                        </select>
                        <input type="text" id="searchInput" onkeyup="filterLibrary()" placeholder="Cari judul/isi..." class="input-field !py-2 !px-3 text-xs w-full md:w-48 border-slate-700">
                    </div>
                </div>

                <!-- DAFTAR TEMPLATE -->
                <div class="space-y-4" id="libraryContainer">
                    @forelse($templates ?? [] as $template)
                        @php
                            // Trik memecah judul dan kategori dari database
                            $parts = explode('||', $template->title);
                            $kategori = count($parts) > 1 ? $parts[0] : 'Umum';
                            $judul = count($parts) > 1 ? $parts[1] : $template->title;
                        @endphp

                        <div class="template-card-item bg-[#0B1120] border border-white/5 rounded-xl p-5 hover:border-indigo-500/30 transition duration-300" data-kategori="{{ strtolower($kategori) }}">
                            <div class="flex justify-between items-start mb-3">
                                <div>
                                    <span class="inline-block bg-indigo-500/10 text-indigo-400 border border-indigo-500/20 text-[10px] font-bold uppercase px-2 py-0.5 rounded mb-1">
                                        {{ $kategori }}
                                    </span>
                                    <h4 class="text-sm font-bold text-white search-title mt-1">{{ $judul }}</h4>
                                </div>
                                <div class="flex gap-2">
                                    <button onclick="deleteTemplate('{{ $template->id }}')" class="bg-red-500/10 hover:bg-red-500 text-red-400 hover:text-white text-[11px] font-semibold px-3 py-1.5 rounded transition">
                                        Hapus
                                    </button>
                                    <button onclick="copyText('{{ $template->id }}', this)" class="bg-white/5 hover:bg-indigo-500 text-slate-300 hover:text-white text-[11px] font-semibold px-3 py-1.5 rounded transition">
                                        Copy
                                    </button>
                                </div>
                            </div>
                            <div class="text-sm text-slate-300 leading-relaxed search-content" id="content-{{ $template->id }}">
                                {!! $template->content !!}
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-24 empty-state">
                            <div class="text-4xl mb-3">📭</div>
                            <p class="text-slate-400 text-sm">Belum ada template.<br>Gunakan form di sebelah kiri untuk mulai.</p>
                        </div>
                    @endforelse

                    <!-- Pesan Jika Filter Kosong -->
                    <div id="noResultMessage" class="hidden text-center py-20">
                        <div class="text-4xl mb-3 opacity-50">🔍</div>
                        <p class="text-slate-400 text-sm">Template tidak ditemukan.</p>
                    </div>
                </div>
            </div>
        </div>

    </main>

    <script>
        // Fitur Live Search & Filter (Tanpa Refresh)
        function filterLibrary() {
            const searchVal = document.getElementById('searchInput').value.toLowerCase();
            const categoryVal = document.getElementById('filterKategori').value.toLowerCase();
            const cards = document.querySelectorAll('.template-card-item');
            let visibleCount = 0;

            cards.forEach(card => {
                const cardKategori = card.getAttribute('data-kategori');
                const cardTitle = card.querySelector('.search-title').innerText.toLowerCase();
                const cardContent = card.querySelector('.search-content').innerText.toLowerCase();

                // Cek apakah cocok dengan pencarian teks ATAU dropdown kategori
                const matchSearch = cardTitle.includes(searchVal) || cardContent.includes(searchVal);
                const matchCategory = categoryVal === "" || cardKategori === categoryVal;

                if (matchSearch && matchCategory) {
                    card.style.display = "block";
                    visibleCount++;
                } else {
                    card.style.display = "none";
                }
            });

            // Tampilkan pesan "Tidak ditemukan" jika hasil filter kosong
            const noResult = document.getElementById('noResultMessage');
            if(visibleCount === 0 && cards.length > 0) {
                noResult.classList.remove('hidden');
            } else {
                noResult.classList.add('hidden');
            }
        }

        // Copy to clipboard
        function copyText(id, btn) {
            const text = document.getElementById('content-' + id).innerText;
            navigator.clipboard.writeText(text).then(() => {
                const ori = btn.innerHTML;
                btn.innerHTML = 'Copied!';
                btn.classList.replace('bg-white/5', 'bg-emerald-500');
                btn.classList.replace('hover:bg-indigo-500', 'hover:bg-emerald-600');
                btn.classList.replace('text-slate-300', 'text-white');
                setTimeout(() => {
                    btn.innerHTML = ori;
                    btn.classList.replace('bg-emerald-500', 'bg-white/5');
                    btn.classList.replace('hover:bg-emerald-600', 'hover:bg-indigo-500');
                    btn.classList.replace('text-white', 'text-slate-300');
                }, 2000);
            });
        }

        // Delete Template
        async function deleteTemplate(id) {
            if (!confirm('Yakin ingin menghapus template ini?')) return;
            try {
                const response = await fetch(`/customer-center/destroy/${id}`, {
                    method: 'DELETE',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                });
                const result = await response.json();
                if (result.success) window.location.reload();
                else alert('❌ Gagal menghapus: ' + result.message);
            } catch (error) {
                alert('Terjadi kesalahan koneksi.');
            }
        }

        // Handle Form Submit
        document.getElementById('templateForm').addEventListener('submit', async function(e) {
            e.preventDefault();

            const btn = document.getElementById('submitBtn');
            const statusDiv = document.getElementById('statusMessage');
            const originalText = btn.innerHTML;

            btn.innerHTML = '✨ MEMPROSES AI...';
            btn.classList.add('opacity-50', 'cursor-not-allowed');
            statusDiv.classList.add('hidden');

            try {
                // 1. Generate AI
                const req1 = await fetch('{{ route("customer-center.generate") }}', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: JSON.stringify({
                        judul: document.querySelector('input[name="judul"]').value,
                        kategori: document.querySelector('select[name="kategori"]').value,
                        pesan_dasar: document.querySelector('textarea[name="isi_pesan"]').value
                    })
                });

                const res1 = await req1.json();
                if(!res1.success) throw new Error(res1.message || 'AI Gagal memproses');

                // 2. Simpan Database (Sekarang Kategori ikut dikirim)
                btn.innerHTML = '💾 MENYIMPAN...';

                const req2 = await fetch('{{ route("customer-center.store") }}', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: JSON.stringify({
                        judul: document.querySelector('input[name="judul"]').value,
                        kategori: document.querySelector('select[name="kategori"]').value, // Data kategori dikirim
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
