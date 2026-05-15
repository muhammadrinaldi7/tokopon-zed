<div class="max-w-3xl mx-auto space-y-6">

    {{-- ─────────────── HEADER ─────────────── --}}
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Tambah Perangkat Buyback</h1>
        <p class="text-sm text-gray-500 mt-1">
            Input harga beli HP dan tier akan ter-assign otomatis sesuai range harga yang sudah dikonfigurasi.
        </p>
    </div>

    {{-- ─────────────── CARD FORM ─────────────── --}}
    <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">

        {{-- Card Header --}}
        <div class="bg-gradient-to-r from-[#1c69d4] to-[#7C74F0] px-6 py-5">
            <p class="text-white/70 text-xs font-bold uppercase tracking-wider">Informasi Perangkat</p>
            <p class="text-white font-semibold text-sm mt-0.5">
                Lengkapi data perangkat di bawah ini
            </p>
        </div>

        <form wire:submit.prevent="save" class="p-6 space-y-5">

            {{-- Brand --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Merek</label>
                    <select wire:model="brand_id"
                        class="w-full rounded-lg border-gray-200 py-2.5 focus:ring-[#1c69d4] focus:border-[#1c69d4] text-sm">
                        <option value="">-- Pilih Merek --</option>
                        @foreach ($brands as $brand)
                            <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                        @endforeach
                    </select>
                    @error('brand_id')
                        <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Model HP</label>
                    <input type="text" wire:model="model_name" placeholder="cth: iPhone 15 Pro Max"
                        class="w-full rounded-lg border-gray-200 py-2.5 focus:ring-[#1c69d4] focus:border-[#1c69d4] text-sm">
                    @error('model_name')
                        <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">RAM
                        <span class="font-normal text-gray-400">(opsional)</span>
                    </label>
                    <input type="text" wire:model="ram" placeholder="cth: 8GB"
                        class="w-full rounded-lg border-gray-200 py-2.5 focus:ring-[#1c69d4] focus:border-[#1c69d4] text-sm">
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Penyimpanan</label>
                    <input type="text" wire:model="storage" placeholder="cth: 256GB"
                        class="w-full rounded-lg border-gray-200 py-2.5 focus:ring-[#1c69d4] focus:border-[#1c69d4] text-sm">
                    @error('storage')
                        <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            {{-- Harga Beli + Auto Tier Detection --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5 items-start">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Harga Beli (Kondisi Sempurna)</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <span class="text-gray-500 text-sm font-bold">Rp</span>
                        </div>
                        <input type="number" wire:model.live="base_price" placeholder="0"
                            class="w-full rounded-lg border-gray-200 py-2.5 pl-10 focus:ring-[#1c69d4] focus:border-[#1c69d4] text-sm">
                    </div>
                    <p class="text-xs text-gray-400 mt-1">
                        Tier akan ter-assign otomatis berdasarkan harga ini.
                    </p>
                    @error('base_price')
                        <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Tier Detection Preview --}}
                <div class="pt-6">
                    @if ($detected_tier_id && $detectedTier)
                        <div class="flex items-start gap-3 p-4 bg-emerald-50 border border-emerald-200 rounded-lg">
                            <div class="flex-shrink-0 w-8 h-8 bg-emerald-500 rounded-full flex items-center justify-center">
                                <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                            <div class="min-w-0">
                                <p class="text-sm font-bold text-emerald-800">Tier Ditemukan!</p>
                                <p class="text-sm text-emerald-700 font-semibold">{{ $detectedTier->name }}</p>
                                <p class="text-xs text-emerald-600 mt-0.5">{{ $detectedTier->price_range_label }}</p>
                            </div>
                        </div>
                    @elseif (!empty($base_price) && is_numeric($base_price) && $base_price > 0)
                        <div class="flex items-start gap-3 p-4 bg-amber-50 border border-amber-200 rounded-lg">
                            <div class="flex-shrink-0 w-8 h-8 bg-amber-400 rounded-full flex items-center justify-center">
                                <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-bold text-amber-800">Tier Tidak Ditemukan</p>
                                <p class="text-xs text-amber-700 mt-0.5">
                                    Tidak ada tier dengan range harga Rp {{ number_format($base_price, 0, ',', '.') }}.
                                    Tambah tier baru di halaman Buyback Tiers.
                                </p>
                            </div>
                        </div>
                    @else
                        <div class="flex items-center gap-3 p-4 bg-gray-50 border border-gray-200 rounded-lg text-gray-400">
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <p class="text-sm">Masukkan harga untuk deteksi tier otomatis.</p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Rules Preview dari Tier yang Ter-detect --}}
            @if ($detectedTier && !empty($detectedTier->rules))
                <div class="bg-gray-50 rounded-lg border border-gray-200 p-4">
                    <p class="text-sm font-bold text-gray-700 mb-3 flex items-center gap-2">
                        <svg class="w-4 h-4 text-[#1c69d4]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                        </svg>
                        Rules dari Tier "{{ $detectedTier->name }}"
                    </p>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach ($detectedTier->rules as $category => $items)
                            <div>
                                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">
                                    {{ $category }}
                                </p>
                                <div class="space-y-1.5">
                                    @foreach ($items as $item)
                                        <div class="flex items-center justify-between text-xs">
                                            <span class="text-gray-600">{{ $item['name'] }}</span>
                                            <span
                                                class="font-bold {{ $item['type'] === 'fixed' ? 'text-rose-500' : 'text-amber-500' }}">
                                                @if ($item['type'] === 'fixed')
                                                    -Rp {{ number_format($item['value'], 0, ',', '.') }}
                                                @else
                                                    -{{ $item['value'] }}%
                                                @endif
                                            </span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Status Aktif --}}
            <div class="flex items-center gap-3 p-4 bg-gray-50 rounded-lg border border-gray-100">
                <input type="checkbox" wire:model="is_active" id="is_active"
                    class="h-4 w-4 text-[#1c69d4] border-gray-300 rounded focus:ring-[#1c69d4]">
                <div>
                    <label for="is_active" class="text-sm font-bold text-gray-700 cursor-pointer">
                        Perangkat Aktif
                    </label>
                    <p class="text-xs text-gray-400">Jika dinonaktifkan, HP ini tidak akan muncul di opsi buyback
                        pelanggan.</p>
                </div>
            </div>

            {{-- Submit --}}
            <div class="pt-2 flex gap-3 border-t border-gray-100">
                <a href="{{ route('admin.buyback.index') }}" wire:navigate
                    class="flex-1 text-center px-4 py-2.5 rounded-lg font-bold text-gray-600 bg-gray-100 hover:bg-gray-200 transition">
                    Batal
                </a>
                <button type="submit"
                    class="flex-1 px-4 py-2.5 rounded-lg font-bold text-white bg-[#1c69d4] hover:bg-[#3f36b8] transition shadow-sm shadow-[#1c69d4]/30">
                    Simpan Perangkat
                </button>
            </div>
        </form>
    </div>

    {{-- All Tiers Reference --}}
    @if ($allTiers->count() > 0)
        <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-5">
            <p class="text-sm font-bold text-gray-700 mb-3">Referensi Tier yang Tersedia</p>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                @foreach ($allTiers as $tier)
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg border border-gray-100">
                        <div>
                            <p class="text-sm font-bold text-gray-800">{{ $tier->name }}</p>
                            <p class="text-xs text-gray-500">{{ $tier->price_range_label }}</p>
                        </div>
                        <span class="text-xs text-gray-400">{{ $tier->devices_count ?? 0 }} HP</span>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>
