<div>
    <div class="flex items-center justify-between mb-8">
        <div>
            <a href="{{ route('admin.sell-phones.index') }}" wire:navigate class="text-sm font-bold text-gray-400 hover:text-[#4E44DB] mb-2 inline-flex items-center gap-1 transition-colors">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Kembali
            </a>
            <h1 class="text-2xl font-bold text-gray-900 mt-1">Detail Penjualan HP #SPL-{{ $sellPhone->id }}</h1>
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
            <span class="px-4 py-2 font-bold uppercase rounded-xl text-sm tracking-wider {{ $statusColors[$sellPhone->status] ?? 'bg-gray-100 text-gray-800' }}">
                Status: {{ str_replace('_', ' ', $sellPhone->status) }}
            </span>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        {{-- Kolom Kiri: Detail Pengajuan --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Info Perangkat --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="font-bold text-lg text-gray-900 border-b border-gray-100 pb-3 mb-4">Informasi Perangkat</h3>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Merek & Model</p>
                        <p class="font-medium text-gray-900">{{ $sellPhone->phone_brand }} {{ $sellPhone->phone_model }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Kapasitas</p>
                        <p class="font-medium text-gray-900">{{ $sellPhone->phone_ram ?? '-' }} RAM / {{ $sellPhone->phone_storage ?? '-' }} Storage</p>
                    </div>
                    <div class="col-span-2 mt-2">
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">Deskripsi Kondisi (Catatan Pelanggan)</p>
                        <div class="p-3 bg-gray-50 rounded-lg text-sm text-gray-700 whitespace-pre-wrap font-medium">
                            {{ $sellPhone->minus_desc ?: 'Tidak ada catatan.' }}
                        </div>
                    </div>
                    
                    @php $photos = $sellPhone->getMedia('photos'); @endphp
                    @if($photos->count() > 0)
                    <div class="col-span-2 mt-2 border-t border-gray-100 pt-4">
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-3">Foto Fisik Unit</p>
                        <div class="grid grid-cols-3 md:grid-cols-4 gap-3">
                            @foreach($photos as $photo)
                                <a href="{{ $photo->getUrl() }}" target="_blank" class="aspect-square rounded-xl overflow-hidden border border-gray-200 block hover:opacity-80 transition cursor-zoom-in shadow-sm">
                                    <img src="{{ $photo->getUrl() }}" class="w-full h-full object-cover">
                                </a>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Info Pelanggan & Rekening --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="font-bold text-lg text-gray-900 border-b border-gray-100 pb-3 mb-4">Informasi Pelanggan & Pembayaran</h3>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Pelanggan</p>
                        <p class="font-medium text-gray-900">{{ $sellPhone->user->name }}</p>
                        <p class="text-sm text-gray-500">{{ $sellPhone->user->email }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Tujuan Transfer</p>
                        @if($sellPhone->bank_name)
                            <p class="font-bold text-emerald-600">{{ $sellPhone->bank_name }}</p>
                            <p class="font-medium text-gray-900">{{ $sellPhone->bank_account_number }}</p>
                            <p class="text-sm text-gray-500">A.N: {{ $sellPhone->bank_account_name }}</p>
                        @else
                            <p class="text-sm text-gray-500 italic">Belum diisi pelanggan.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Kolom Kanan: Aksi --}}
        <div class="space-y-6">
            {{-- Form Penaksiran Harga --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="font-bold text-lg text-gray-900 border-b border-gray-100 pb-3 mb-4">Taksiran Harga Admin</h3>
                
                @if($sellPhone->appraised_value)
                    <div class="mb-4 p-4 bg-emerald-50 border border-emerald-100 rounded-xl text-center">
                        <p class="text-xs font-bold text-emerald-600 uppercase tracking-widest mb-1">Nilai Ditawarkan</p>
                        <p class="text-2xl font-black text-emerald-700">Rp {{ number_format($sellPhone->appraised_value, 0, ',', '.') }}</p>
                    </div>
                @endif

                @if(!in_array($sellPhone->status, ['COMPLETED', 'CANCELLED']))
                    <form wire:submit="submitAppraisal" class="space-y-4">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">Update Penawaran Harga (Rp)</label>
                            <input type="number" wire:model="appraisedValue" class="w-full rounded-lg border-gray-200 focus:ring-[#4E44DB] focus:border-[#4E44DB]">
                        </div>
                        <button type="submit" class="w-full bg-[#4E44DB] text-white py-2.5 rounded-lg font-bold hover:bg-[#3f36b8] transition">
                            Simpan Penawaran
                        </button>
                    </form>
                @endif
            </div>

            {{-- Aksi Lainnya --}}
            @if(!in_array($sellPhone->status, ['COMPLETED', 'CANCELLED']))
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="font-bold text-lg text-gray-900 border-b border-gray-100 pb-3 mb-4">Aksi Transaksi</h3>
                
                <div class="space-y-3">
                    <button type="button" wire:click="markAsPaid" wire:confirm="Apakah Anda yakin sudah mentransfer pelunasan ke pelanggan dan menerima unit fisik secara lengkap?" class="w-full bg-emerald-500 text-white py-2.5 rounded-lg font-bold hover:bg-emerald-600 transition flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        Tandai Selesai / Lunas
                    </button>

                    <button type="button" wire:click="reject" wire:confirm="Yakin ingin menolak penawaran ini?" class="w-full bg-white border-2 border-rose-100 text-rose-600 py-2.5 rounded-lg font-bold hover:bg-rose-50 transition">
                        Tolak / Batalkan
                    </button>
                </div>
            </div>
            @endif

            {{-- Konversi Inventaris --}}
            @if($sellPhone->status === 'COMPLETED' && ! \App\Models\ProductVariant::where('sell_phone_id', $sellPhone->id)->exists())
                <div class="bg-purple-50 rounded-xl border border-purple-100 p-6 animate-in zoom-in duration-300">
                    <h3 class="font-bold text-lg text-purple-900 mb-2">Masuk Ke Inventaris</h3>
                    <p class="text-sm text-purple-700 mb-4">Transaksi sudah lunas. Daftarkan HP ini ke etalase toko sebagai barang seken (Second Hand) agar bisa langsung dibeli orang lain.</p>
                    
                    <button type="button" wire:click="$set('convertModal', true)" class="w-full bg-purple-600 text-white py-2.5 rounded-lg font-bold hover:bg-purple-700 transition">
                        Jual Sebagai Barang Second
                    </button>
                </div>

                {{-- Modal Konversi --}}
                @if($convertModal)
                    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm" wire:click.self="$set('convertModal', false)">
                        <div class="bg-white rounded-3xl shadow-2xl w-full max-w-md mx-4 overflow-hidden">
                            <div class="bg-gradient-to-r from-purple-500 to-indigo-500 px-6 py-5 text-white">
                                <h2 class="text-xl font-bold">Daftarkan Produk Second</h2>
                                <p class="text-purple-100 text-sm mt-1">{{ $sellPhone->phone_brand }} {{ $sellPhone->phone_model }}</p>
                            </div>
                            <form wire:submit="convertToProduct" class="p-6 space-y-5">
                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-sm font-bold text-gray-700 mb-1">Harga Jual Baru (Rp)</label>
                                        <div class="relative">
                                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                                <span class="text-gray-500 font-bold">Rp</span>
                                            </div>
                                            <input type="number" wire:model="sellPrice" class="pl-12 w-full text-lg font-bold rounded-xl border-gray-200 py-3 focus:ring-2 focus:ring-purple-500/30 focus:border-purple-500" placeholder="0" required>
                                        </div>
                                        @error('sellPrice') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                                    </div>
                                    <div>
                                        <label class="block text-sm font-bold text-gray-700 mb-1">Kondisi</label>
                                        <select wire:model="secondCondition" class="w-full rounded-xl border-gray-200 py-3 focus:ring-purple-500 focus:border-purple-500">
                                            <option value="Like New">Like New</option>
                                            <option value="Bekas (Mulus)">Bekas (Mulus)</option>
                                            <option value="Bekas (Ada Minus)">Bekas (Ada Minus)</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="mt-6 flex justify-end gap-3">
                                    <button type="button" wire:click="$set('convertModal', false)" class="flex-1 px-4 py-3 font-bold text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-xl transition">Batal</button>
                                    <button type="submit" class="flex-1 px-4 py-3 font-bold text-white bg-purple-600 hover:bg-purple-700 rounded-xl transition shadow-lg shadow-purple-500/25">Simpan ke Katalog</button>
                                </div>
                            </form>
                        </div>
                    </div>
                @endif
            @elseif(\App\Models\ProductVariant::where('sell_phone_id', $sellPhone->id)->exists())
                <div class="bg-emerald-50 rounded-xl border border-emerald-100 p-6 text-center">
                    <div class="w-12 h-12 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center mx-auto mb-3">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                    </div>
                    <h3 class="font-bold text-emerald-900">Telah Masuk Katalog</h3>
                    <p class="text-sm text-emerald-700 mt-1">HP ini sudah didaftarkan sebagai varian produk second dan siap dijual.</p>
                </div>
            @endif
        </div>
    </div>
</div>
