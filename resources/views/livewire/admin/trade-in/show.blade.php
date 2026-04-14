<div>
    <div class="flex items-center justify-between mb-8">
        <div>
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.trade-ins.index') }}" wire:navigate class="text-gray-400 hover:text-gray-600 transition">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                </a>
                <h1 class="text-2xl font-bold text-gray-900">Detail Pengajuan #TRD-{{ $tradeIn->id }}</h1>
            </div>
            <p class="text-sm text-gray-500 mt-1 ml-9">Pelanggan: <span class="font-bold text-gray-700">{{ $tradeIn->user->name }}</span> ({{ $tradeIn->created_at->format('d M Y, H:i') }})</p>
        </div>
        <div>
            @php
                $statusColors = [
                    'PENDING' => 'bg-amber-100 text-amber-800',
                    'OFFERED' => 'bg-blue-100 text-blue-800',
                    'WAITING_FOR_DEVICE' => 'bg-purple-100 text-purple-800',
                    'INSPECTING' => 'bg-indigo-100 text-indigo-800',
                    'PAYING' => 'bg-teal-100 text-teal-800',
                    'COMPLETED' => 'bg-emerald-100 text-emerald-800',
                    'CANCELLED' => 'bg-rose-100 text-rose-800',
                ];
            @endphp
            <span class="px-4 py-2 font-bold uppercase rounded-lg tracking-wider border border-white/20 shadow-sm {{ $statusColors[$tradeIn->status] ?? 'bg-gray-100 text-gray-800' }}">
                Status: {{ str_replace('_', ' ', $tradeIn->status) }}
            </span>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        {{-- LEFT PANEL: Customer Old Phone Details --}}
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h3 class="font-bold text-gray-900 text-lg mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" /></svg>
                    Informasi HP Lama (Milik User)
                </h3>
                
                <div class="space-y-4">
                    <div>
                        <p class="text-xs text-gray-400 font-bold uppercase tracking-wider">Merek & Tipe</p>
                        <p class="font-bold text-gray-900 text-lg">{{ $tradeIn->old_phone_brand }} {{ $tradeIn->old_phone_model }}</p>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div class="bg-gray-50 rounded-xl p-3 border border-gray-100">
                            <p class="text-[10px] text-gray-400 font-bold uppercase">RAM</p>
                            <p class="font-semibold text-gray-800">{{ $tradeIn->old_phone_ram ?? '-' }}</p>
                        </div>
                        <div class="bg-gray-50 rounded-xl p-3 border border-gray-100">
                            <p class="text-[10px] text-gray-400 font-bold uppercase">Storage</p>
                            <p class="font-semibold text-gray-800">{{ $tradeIn->old_phone_storage ?? '-' }}</p>
                        </div>
                    </div>
                    
                    <div>
                        <p class="text-xs text-gray-400 font-bold uppercase tracking-wider mb-1">Deskripsi Minus/Kondisi</p>
                        <div class="bg-rose-50 border border-rose-100 rounded-xl p-4 text-sm text-rose-800">
                            {!! nl2br(e($tradeIn->old_phone_minus_desc)) !!}
                        </div>
                    </div>
                </div>
            </div>

            {{-- Photos --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h3 class="font-bold text-gray-900 text-lg mb-4">Foto Fisik Unit Asli</h3>
                @php $photos = $tradeIn->getMedia('customer_unit_photos'); @endphp
                @if($photos->count() > 0)
                    <div class="grid grid-cols-2 gap-3">
                        @foreach($photos as $photo)
                            <a href="{{ $photo->getUrl() }}" target="_blank" class="aspect-square rounded-xl overflow-hidden border border-gray-200 block hover:opacity-80 transition cursor-zoom-in">
                                <img src="{{ $photo->getUrl() }}" class="w-full h-full object-cover">
                            </a>
                        @endforeach
                    </div>
                @else
                    <p class="text-sm text-gray-500 italic">Pengguna tidak melampirkan foto fisik.</p>
                @endif
            </div>

            {{-- Tracking Resi --}}
            @if($tradeIn->customer_shipping_receipt)
                <div class="bg-[#4E44DB]/5 rounded-2xl border border-[#4E44DB]/20 p-6">
                    <h3 class="font-bold text-[#4E44DB] mb-1">Resi Pengiriman Pelanggan</h3>
                    <p class="text-sm text-gray-600 mb-2">Pelanggan telah mengirim unit lama ke toko kita via ekspedisi.</p>
                    <div class="bg-white px-4 py-3 rounded-xl border border-gray-200 font-mono text-gray-800 font-bold tracking-widest text-center shadow-sm">
                        {{ $tradeIn->customer_shipping_receipt }}
                    </div>
                </div>
            @endif
        </div>

        {{-- RIGHT PANEL: Appraisal & Actions --}}
        <div class="lg:col-span-2 space-y-6">
            
            @if(in_array($tradeIn->status, ['PENDING', 'OFFERED', 'INSPECTING']))
                {{-- Form Penaksiran --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 {{ $tradeIn->status === 'INSPECTING' ? 'mt-6 border-amber-200 bg-amber-50/30' : '' }}">
                    <h3 class="font-bold text-gray-900 text-xl mb-1">
                        {{ $tradeIn->status === 'INSPECTING' ? 'Revisi Penawaran' : 'Formulir Appraisal & Penawaran' }}
                    </h3>
                    <p class="text-sm text-gray-500 mb-6">
                        @if($tradeIn->status === 'INSPECTING')
                            <span class="text-amber-600 font-medium">Jika fisik unit yang datang tidak sesuai dengan deskripsi awal, Anda dapat merevisi taksiran nilai di sini. Status akan kembali ke tahap Penawaran.</span>
                        @else
                            Tentukan nilai beli HP lama dan pilih unit gudang yang akan ditawarkan ke pengguna.
                        @endif
                    </p>

                    <form wire:submit="submitAppraisal">
                        <div class="mb-6">
                            <label class="block text-sm font-bold text-gray-700 mb-2">Taksiran Nilai Beli HP Lama <span class="text-rose-500">*</span></label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <span class="text-gray-500 font-bold">Rp</span>
                                </div>
                                <input type="number" wire:model="appraisedValue" class="pl-12 w-full text-lg font-bold rounded-xl border-gray-200 py-3 focus:ring-2 focus:ring-[#4E44DB]/20 focus:border-[#4E44DB]" placeholder="0">
                            </div>
                            <p class="text-xs text-gray-400 mt-2">Harga ini yang akan dipakai untuk memotong harga unit HP baru.</p>
                            @error('appraisedValue') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <hr class="border-gray-100 my-6">

                        <div class="mb-4 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                            <div>
                                <h4 class="font-bold text-gray-900">Pilih Unit Kandidat (Max 3)</h4>
                                <p class="text-sm text-gray-500">Kandidat unit produk incaran: <span class="font-bold text-[#4E44DB]">{{ $tradeIn->targetProduct->name }}</span></p>
                            </div>
                            <div class="relative max-w-xs">
                                <input type="text" wire:model.live.debounce.300ms="searchVariant" placeholder="Cari warna/storage..." class="w-full text-sm rounded-lg border-gray-200 py-2 focus:ring-[#4E44DB] focus:border-[#4E44DB]">
                            </div>
                        </div>
                        @error('selectedVariants') <span class="text-sm text-rose-500 font-bold block mb-3 bg-rose-50 p-2 rounded-lg">{{ $message }}</span> @enderror

                        <div class="space-y-3 mb-8 max-h-80 overflow-y-auto pr-2">
                            @forelse($availableVariants as $variant)
                                @php
                                    $isSelected = in_array($variant->id, $selectedVariants);
                                @endphp
                                <label class="block cursor-pointer">
                                    <div class="flex items-center gap-4 p-4 rounded-xl border-2 transition-all {{ $isSelected ? 'border-[#4E44DB] bg-[#4E44DB]/5' : 'border-gray-100 hover:border-gray-300' }}">
                                        <div class="w-5 h-5 rounded border {{ $isSelected ? 'bg-[#4E44DB] border-[#4E44DB]' : 'border-gray-300' }} flex items-center justify-center shrink-0">
                                            @if($isSelected)
                                                <svg class="w-3.5 h-3.5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                                            @endif
                                        </div>
                                        <div class="flex-1">
                                            <div class="flex items-center gap-2">
                                                <h5 class="font-bold text-gray-900">{{ $variant->color }} - {{ $variant->storage }}</h5>
                                                <span class="text-[10px] font-bold px-2 py-0.5 rounded-md bg-gray-100 text-gray-600">{{ $variant->condition ?? 'Bekas' }}</span>
                                            </div>
                                            <p class="text-sm font-black text-[#4E44DB] mt-1">Harga: Rp {{ number_format($variant->price, 0, ',', '.') }}</p>
                                        </div>
                                        <div class="text-right">
                                            <p class="text-[10px] text-gray-400 font-bold uppercase mb-1">Estimasi Tambahan dari Klien</p>
                                            @php
                                                $topup = max(0, $variant->price - (float) ($appraisedValue ?: 0));
                                            @endphp
                                            <span class="font-bold {{ $topup > 0 ? 'text-amber-600' : 'text-emerald-500' }}">Rp {{ number_format($topup, 0, ',', '.') }}</span>
                                        </div>
                                    </div>
                                    <input type="checkbox" class="hidden" wire:click="toggleVariant({{ $variant->id }})" value="{{ $variant->id }}">
                                </label>
                            @empty
                                <div class="text-center py-6 bg-gray-50 rounded-xl border border-gray-200 border-dashed">
                                    <p class="text-sm text-gray-500">Tidak ada unit (IMEI) yang tersedia dengan stok > 0 untuk produk ini.</p>
                                </div>
                            @endforelse
                        </div>

                        <div class="flex gap-4">
                            <button type="button" wire:click="reject" wire:confirm="Yakin ingin membatalkan aplikasi trade-in secara sepihak?" class="px-6 py-3.5 rounded-xl font-bold bg-white border border-rose-200 text-rose-600 hover:bg-rose-50 transition">
                                Tolak & Batalkan
                            </button>
                            <button type="submit" class="flex-1 bg-[#4E44DB] text-white py-3.5 rounded-xl font-bold hover:bg-[#3f36b8] transition shadow-lg shadow-[#4E44DB]/25">
                                {{ $tradeIn->status === 'OFFERED' ? 'Perbarui Penawaran' : ($tradeIn->status === 'INSPECTING' ? 'Kirim Revisi Penawaran' : 'Kirim Penawaran ke Konsumen') }}
                            </button>
                        </div>
                    </form>
                </div>
            @endif

            @if($tradeIn->status === 'INSPECTING')
                <div class="bg-indigo-50 border border-indigo-100 rounded-2xl p-6 relative overflow-hidden">
                    <div class="absolute -right-10 -top-10 text-indigo-100 opacity-50">
                        <svg class="w-48 h-48" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </div>
                    <div class="relative z-10">
                        <h3 class="font-bold text-indigo-900 text-xl mb-2">Inspeksi Fisik Unit</h3>
                        <p class="text-sm text-indigo-700 mb-6">Resi pengiriman pengguna sudah aktif ({{ $tradeIn->customer_shipping_receipt }}). Cocokkan fisik HP yang Anda terima dengan foto & deskripsi awal.</p>
                        
                        <div class="bg-white rounded-xl p-5 shadow-sm border border-indigo-50 flex gap-4">
                            <button type="button" wire:click="reject" wire:confirm="Yakin membatalkan transaksi? Sistem akan mengembalikan unit ke pelanggan tanpa invoice." class="flex-1 px-6 py-3 rounded-lg font-bold bg-rose-50 text-rose-600 hover:bg-rose-100 transition">
                                Fisik Tidak Sesuai (Tolak)
                            </button>
                            <button type="button" wire:click="markAsPhysicallyVerified" wire:confirm="Sistem akan langsung menagih pembayaran sisa kepada Klien. Lanjutkan?" class="flex-1 px-6 py-3 rounded-lg font-bold bg-indigo-600 text-white hover:bg-indigo-700 shadow-md shadow-indigo-600/30 transition">
                                Fisik Lolos Validasi (Setuju)
                            </button>
                        </div>
                    </div>
                </div>
            @endif
            
            @if(in_array($tradeIn->status, ['WAITING_FOR_DEVICE', 'PAYING', 'COMPLETED', 'CANCELLED']))
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 opacity-70">
                    <h3 class="font-bold text-gray-900 mb-2">Penawaran yang Terkunci</h3>
                    <p class="text-sm text-gray-500 mb-4">Konsumen sudah menyetujui salah satu unit atau transaksi sudah berjalan melebihi tahap penawaran. Anda tidak bisa lagi merevisi appraisal.</p>
                    <div class="text-2xl font-black text-emerald-600 mb-4">
                        Taksiran Disetujui: Rp {{ number_format($tradeIn->appraised_value, 0, ',', '.') }}
                    </div>
                </div>
            @endif

            {{-- Tombol Konversi ke Produk Second --}}
            @if($tradeIn->status === 'COMPLETED')
                @php
                    $alreadyConverted = \App\Models\ProductVariant::where('trade_in_id', $tradeIn->id)->exists();
                @endphp

                @if($alreadyConverted)
                    <div class="bg-emerald-50 border border-emerald-200 rounded-2xl p-6">
                        <div class="flex items-center gap-3 mb-2">
                            <svg class="w-6 h-6 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            <h3 class="font-bold text-emerald-800 text-lg">Sudah Dikonversi ke Katalog Second</h3>
                        </div>
                        <p class="text-sm text-emerald-700">Unit HP lama dari trade-in ini sudah masuk ke inventaris produk bekas dan siap dijual di toko online.</p>
                    </div>
                @else
                    <div class="bg-gradient-to-br from-amber-50 to-orange-50 border border-amber-200 rounded-2xl p-6">
                        <h3 class="font-bold text-amber-900 text-xl mb-2">Konversi HP Lama ke Produk Second</h3>
                        <p class="text-sm text-amber-700 mb-5">Trade-in selesai. HP lama pelanggan kini jadi milik toko. Masukkan ke katalog produk bekas agar bisa dijual kembali secara online.</p>
                        <button wire:click="$set('convertModal', true)" class="bg-amber-500 text-white px-6 py-3 rounded-xl font-bold hover:bg-amber-600 transition shadow-lg shadow-amber-500/25">
                            <svg class="w-5 h-5 inline-block mr-1 -mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" /></svg>
                            Jual Sebagai Produk Second
                        </button>
                    </div>
                @endif
            @endif

        </div>
    </div>

    {{-- Modal Konversi --}}
    @if($convertModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm" wire:click.self="$set('convertModal', false)">
            <div class="bg-white rounded-3xl shadow-2xl w-full max-w-lg mx-4 overflow-hidden">
                <div class="bg-gradient-to-r from-amber-500 to-orange-500 px-6 py-5 text-white">
                    <h2 class="text-xl font-bold">Konversi ke Produk Second</h2>
                    <p class="text-amber-100 text-sm mt-1">{{ $tradeIn->old_phone_brand }} {{ $tradeIn->old_phone_model }} — {{ $tradeIn->old_phone_storage ?? '' }}</p>
                </div>
                <form wire:submit="convertToProduct" class="p-6 space-y-5">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Harga Jual (Dipasang oleh Manajer) <span class="text-rose-500">*</span></label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <span class="text-gray-500 font-bold">Rp</span>
                            </div>
                            <input type="number" wire:model="sellPrice" class="pl-12 w-full text-lg font-bold rounded-xl border-gray-200 py-3 focus:ring-2 focus:ring-amber-500/30 focus:border-amber-500" placeholder="0" required>
                        </div>
                        @error('sellPrice') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Kondisi Fisik</label>
                        <select wire:model="secondCondition" class="w-full rounded-xl border-gray-200 py-3 focus:ring-amber-500 focus:border-amber-500">
                            <option value="Bekas - Mulus">Bekas - Mulus</option>
                            <option value="Bekas - Normal">Bekas - Normal</option>
                            <option value="Bekas - Minus">Bekas - Minus</option>
                            <option value="Bekas">Bekas</option>
                        </select>
                    </div>

                    <div class="bg-gray-50 rounded-xl p-4 border border-gray-100 text-sm text-gray-600">
                        <p><strong>Info:</strong> Ini akan membuat entri <strong>Produk</strong> baru bernama <em>{{ $tradeIn->old_phone_brand }} {{ $tradeIn->old_phone_model }}</em> dengan flag <code>is_second = true</code>, dan 1 varian fisik berstok 1 unit.</p>
                    </div>

                    <div class="flex gap-3 pt-2">
                        <button type="button" wire:click="$set('convertModal', false)" class="flex-1 py-3 rounded-xl font-bold bg-gray-100 text-gray-600 hover:bg-gray-200 transition">Batal</button>
                        <button type="submit" class="flex-1 py-3 rounded-xl font-bold bg-amber-500 text-white hover:bg-amber-600 transition shadow-lg shadow-amber-500/25">
                            <span wire:loading.remove wire:target="convertToProduct">Simpan & Masukkan Katalog</span>
                            <span wire:loading wire:target="convertToProduct">Menyimpan...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
