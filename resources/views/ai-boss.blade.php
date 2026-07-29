<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('AI Boss Chat') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if(session('error'))
                <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50">
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-t-4 border-indigo-600">
                <div class="p-8 text-center">
                    <div class="text-6xl mb-4">🤖</div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">Halo, Bos!</h3>
                    <p class="text-gray-600 mb-8">Pilih target utama bisnis Anda hari ini, dan saya akan buatkan Action Plan serta To-Do List otomatis untuk Anda.</p>

                    <form method="POST" action="{{ route('aiboss.generate') }}" class="max-w-2xl mx-auto">
                        @csrf

                        <!-- Pilihan Cepat (Intent Selector) -->
                        <div class="grid grid-cols-2 gap-4 mb-6">
                            <button type="submit" name="intent" value="Naikkan Penjualan Cepat Hari Ini" class="p-4 border-2 border-gray-200 rounded-xl hover:border-indigo-500 hover:bg-indigo-50 transition text-left">
                                <span class="block text-xl mb-1">📈</span>
                                <span class="font-bold text-gray-800">Kejar Omzet</span>
                                <span class="block text-xs text-gray-500">Strategi promo kilat</span>
                            </button>

                            <button type="submit" name="intent" value="Persiapan Live Streaming Jualan" class="p-4 border-2 border-gray-200 rounded-xl hover:border-indigo-500 hover:bg-indigo-50 transition text-left">
                                <span class="block text-xl mb-1">🛍️</span>
                                <span class="font-bold text-gray-800">Persiapan Live</span>
                                <span class="block text-xs text-gray-500">Checklist & script live</span>
                            </button>

                            <button type="submit" name="intent" value="Merapikan Arus Kas dan HPP" class="p-4 border-2 border-gray-200 rounded-xl hover:border-indigo-500 hover:bg-indigo-50 transition text-left">
                                <span class="block text-xl mb-1">💰</span>
                                <span class="font-bold text-gray-800">Rapikan Keuangan</span>
                                <span class="block text-xs text-gray-500">Audit margin & biaya</span>
                            </button>

                            <button type="submit" name="intent" value="Meningkatkan Retensi Pelanggan" class="p-4 border-2 border-gray-200 rounded-xl hover:border-indigo-500 hover:bg-indigo-50 transition text-left">
                                <span class="block text-xl mb-1">💬</span>
                                <span class="font-bold text-gray-800">Follow-Up Customer</span>
                                <span class="block text-xs text-gray-500">Strategi repeat order</span>
                            </button>
                        </div>

                        <div class="relative">
                            <div class="absolute inset-0 flex items-center" aria-hidden="true">
                                <div class="w-full border-t border-gray-300"></div>
                            </div>
                            <div class="relative flex justify-center">
                                <span class="px-2 bg-white text-sm text-gray-500">Atau ketik bebas target Anda</span>
                            </div>
                        </div>

                        <div class="mt-6 flex gap-2">
                            <input type="text" name="custom_intent" class="flex-1 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" placeholder="Ketik masalah spesifik bisnis Anda di sini...">
                            <button type="submit" name="intent" value="custom" onclick="this.value = this.previousElementSibling.value" class="bg-gray-800 hover:bg-gray-700 text-white px-6 py-2 rounded-md font-semibold transition">
                                Tanya AI
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
