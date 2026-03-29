<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-800">Manajemen Produk</h1>
        <button wire:click="create" class="bg-[#4E44DB] text-white px-4 py-2 rounded-xl text-sm font-semibold hover:bg-opacity-90 transition">
            Tambah Produk
        </button>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="w-full text-left text-sm whitespace-nowrap">
            <thead class="bg-gray-50 text-gray-600 font-semibold border-b border-gray-100">
                <tr>
                    <th class="px-6 py-4">Produk</th>
                    <th class="px-6 py-4">Status Erzap</th>
                    <th class="px-6 py-4">Total Stok</th>
                    <th class="px-6 py-4">Harga Termurah</th>
                    <th class="px-6 py-4 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($products as $product)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4">
                            <button wire:click="viewDetail({{ $product->id }})" class="font-bold text-[#4E44DB] hover:text-[#3f36b8] hover:underline text-left transition-colors">
                                {{ $product->name }}
                            </button>
                        </td>
                        <td class="px-6 py-4">
                            @if($product->has_active_erzap)
                                <span class="bg-emerald-100 text-emerald-700 font-bold px-2.5 py-1 rounded-full text-xs">Aktif ✓</span>
                            @else
                                <span class="bg-gray-100 text-gray-600 font-bold px-2.5 py-1 rounded-full text-xs">Belum Link</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">{{ $product->total_stock }} Unit</td>
                        <td class="px-6 py-4">Rp. {{ number_format($product->starting_price ?? 0, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('admin.products.variants', $product->id) }}" wire:navigate class="text-[#4E44DB] font-semibold text-xs border border-[#4E44DB] px-3 py-1.5 rounded-lg hover:bg-[#eff2ff] mr-2 transition inline-flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                                </svg>
                                Varian
                            </a>
                            <button wire:click="edit({{ $product->id }})" class="text-gray-500 hover:text-gray-800 transition mr-2">
                                Edit
                            </button>
                            <button wire:click="delete({{ $product->id }})" class="text-rose-500 hover:text-rose-700 transition" onclick="confirm('Yakin ingin menghapus produk ini beserta seluruh variannya?') || event.stopImmediatePropagation()">
                                Hapus
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-gray-500">Belum ada produk.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        @if($products->hasPages())
            <div class="p-4 border-t border-gray-100">
                {{ $products->links() }}
            </div>
        @endif
    </div>

    {{-- Modal Create/Edit --}}
    @if($showModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-0 transition-opacity" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            {{-- Backdrop with blur --}}
            <div wire:click="$set('showModal', false)" class="fixed inset-0 bg-gray-900/40 backdrop-blur-sm transition-opacity"></div>

            {{-- Modal Panel --}}
            <div class="relative transform overflow-hidden rounded-4xl bg-white/80 backdrop-blur-2xl border border-white shadow-2xl shadow-[#4E44DB]/15 text-left transition-all sm:my-8 sm:w-full sm:max-w-md">
                
                {{-- Header --}}
                <div class="px-6 py-5 border-b border-gray-200/50 flex justify-between items-center backdrop-blur-md bg-white/40">
                    <h2 class="text-[17px] font-semibold tracking-tight text-gray-900">{{ $isEditing ? 'Edit Produk Utama' : 'Tambah Produk Baru' }}</h2>
                    <button wire:click="$set('showModal', false)" class="text-gray-400 hover:text-gray-600 bg-gray-100/50 hover:bg-gray-200/50 rounded-full p-1.5 transition-colors focus:outline-none">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>
                
                {{-- Form Body --}}
                <form wire:submit.prevent="store" class="p-6 space-y-5">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5 ml-1">Nama Produk Utama</label>
                        <input type="text" wire:model="name" placeholder="Contoh: iPhone 15 Pro Max" class="w-full text-[15px] bg-white/60 border border-gray-200/70 focus:bg-white focus:border-[#4E44DB] focus:ring-4 focus:ring-[#4E44DB]/10 rounded-2xl px-4 py-3 shadow-sm transition-all text-gray-800 placeholder-gray-400" required>
                        @error('name') <span class="text-xs text-rose-500 font-medium ml-1 mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5 ml-1">Kategori Produk <span class="text-rose-500">*</span></label>
                            <select wire:model="categoryId" class="w-full text-[15px] bg-white/60 border border-gray-200/70 focus:bg-white focus:border-[#4E44DB] focus:ring-4 focus:ring-[#4E44DB]/10 rounded-2xl px-4 py-3 shadow-sm transition-all text-gray-800" required>
                                <option value="">Pilih Kategori...</option>
                                @foreach($categoriesList as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                            @error('categoryId') <span class="text-xs text-rose-500 font-medium ml-1 mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5 ml-1">Merek (opsional)</label>
                            <select wire:model="brandId" class="w-full text-[15px] bg-white/60 border border-gray-200/70 focus:bg-white focus:border-[#4E44DB] focus:ring-4 focus:ring-[#4E44DB]/10 rounded-2xl px-4 py-3 shadow-sm transition-all text-gray-800">
                                <option value="">Tanpa Merek / Lainnya...</option>
                                @foreach($brandsList as $b)
                                    <option value="{{ $b->id }}">{{ $b->name }}</option>
                                @endforeach
                            </select>
                            @error('brandId') <span class="text-xs text-rose-500 font-medium ml-1 mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5 ml-1">Deskripsi Singkat</label>
                        <textarea wire:model="description" rows="3" class="w-full text-[15px] bg-white/60 border border-gray-200/70 focus:bg-white focus:border-[#4E44DB] focus:ring-4 focus:ring-[#4E44DB]/10 rounded-2xl px-4 py-3 shadow-sm transition-all text-gray-800 placeholder-gray-400 resize-none" placeholder="(Opsional) Masukkan deskripsi produk..."></textarea>
                    </div>

                    {{-- Dynamic Specifications --}}
                    <div class="pt-2 border-t border-gray-100">
                        <div class="flex items-center justify-between mb-2 ml-1">
                            <label class="block text-sm font-medium text-gray-700">Spesifikasi Master</label>
                            <button type="button" wire:click="addSpecification" class="text-xs font-bold text-[#4E44DB] hover:text-[#3f36b8] bg-[#eff2ff] px-2 py-1 rounded-lg transition-colors">
                                + Tambah Atribut
                            </button>
                        </div>
                        
                        <div class="space-y-2 max-h-40 overflow-y-auto pr-1">
                            @forelse($specifications as $index => $spec)
                                <div class="flex gap-2 items-center">
                                    <input type="text" wire:model="specifications.{{ $index }}.key" placeholder="Ex: Kamera" class="w-1/3 text-[13px] bg-white/60 border border-gray-200/70 focus:border-[#4E44DB] rounded-xl px-3 py-2 shadow-sm transition-all" required>
                                    <input type="text" wire:model="specifications.{{ $index }}.value" placeholder="Ex: 48 MP Mumpuni" class="flex-1 text-[13px] bg-white/60 border border-gray-200/70 focus:border-[#4E44DB] rounded-xl px-3 py-2 shadow-sm transition-all" required>
                                    <button type="button" wire:click="removeSpecification({{ $index }})" class="text-rose-400 hover:text-rose-600 p-1.5 rounded-lg hover:bg-rose-50 transition-colors focus:outline-none">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                    </button>
                                </div>
                            @empty
                                <div class="text-[13px] text-gray-400 italic text-center py-2 bg-gray-50/50 rounded-xl border border-dashed border-gray-200">
                                    Belum ada spesifikasi tambahan.
                                </div>
                            @endforelse
                        </div>
                    </div>
                    
                    {{-- Actions --}}
                    <div class="pt-4 flex gap-3">
                        <button type="button" wire:click="$set('showModal', false)" class="flex-1 bg-gray-100/50 hover:bg-gray-200/70 text-gray-700 py-3 rounded-2xl text-[15px] font-semibold transition-all">
                            Batal
                        </button>
                        <button type="submit" class="flex-1 bg-[#4E44DB] text-white py-3 rounded-2xl text-[15px] font-semibold hover:bg-[#3f36b8] hover:shadow-lg hover:shadow-[#4E44DB]/30 active:scale-[0.98] transition-all">
                            Simpan 
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    {{-- Modal Detail --}}
    @if($showDetailModal && $detailProduct)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-0 transition-opacity" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div wire:click="$set('showDetailModal', false)" class="fixed inset-0 bg-gray-900/40 backdrop-blur-sm transition-opacity"></div>

            <div class="relative transform overflow-hidden rounded-4xl bg-white/80 backdrop-blur-2xl border border-white shadow-2xl shadow-[#4E44DB]/15 text-left transition-all sm:my-8 w-full max-w-lg">
                {{-- Header --}}
                <div class="px-6 py-5 border-b border-gray-200/50 flex justify-between items-center backdrop-blur-md bg-white/40">
                    <h2 class="text-[17px] font-semibold tracking-tight text-gray-900">Detail Produk</h2>
                    <button wire:click="$set('showDetailModal', false)" class="text-gray-400 hover:text-gray-600 bg-gray-100/50 hover:bg-gray-200/50 rounded-full p-1.5 transition-colors focus:outline-none">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>

                {{-- Body --}}
                <div class="p-6 space-y-5 max-h-[75vh] overflow-y-auto">
                    <div>
                        <div class="flex items-center gap-3 mb-1.5">
                            <h3 class="text-xl font-bold text-[#4E44DB]">{{ $detailProduct->name }}</h3>
                            @if($detailProduct->has_active_erzap)
                                <span class="bg-emerald-100 text-emerald-700 font-bold px-2.5 py-1 rounded-full text-[10px] uppercase tracking-wider shrink-0">Terkoneksi Erzap</span>
                            @endif
                        </div>
                        <div class="flex gap-2 mb-3">
                            <span class="bg-[#eff2ff] text-[#4E44DB] px-2.5 py-1 rounded-lg text-[11px] font-bold tracking-wide uppercase">{{ $detailProduct->category?->name ?? 'Tanpa Kategori' }}</span>
                            @if($detailProduct->brand)
                                <span class="bg-gray-100 text-gray-600 px-2.5 py-1 rounded-lg text-[11px] font-bold tracking-wide uppercase">{{ $detailProduct->brand->name }}</span>
                            @endif
                        </div>
                        <p class="text-sm text-gray-500 leading-relaxed">{{ $detailProduct->description ?: 'Tidak ada deskripsi.' }}</p>
                    </div>

                    <div class="grid grid-cols-2 gap-4 pt-4 border-t border-gray-100">
                        <div class="bg-[#eff2ff]/50 p-4 rounded-2xl border border-[#4E44DB]/10">
                            <p class="text-xs text-[#4E44DB] font-bold tracking-wide uppercase mb-1">Total Stok</p>
                            <p class="text-2xl font-black text-gray-800">{{ $detailProduct->total_stock }} <span class="text-sm font-medium text-gray-500">Unit</span></p>
                        </div>
                        <div class="bg-emerald-50/50 p-4 rounded-2xl border border-emerald-100">
                            <p class="text-xs text-emerald-600 font-bold tracking-wide uppercase mb-1">Harga Mulai</p>
                            <p class="text-2xl font-black text-gray-800"><span class="text-sm font-medium text-gray-500 mr-1">Rp</span>{{ number_format($detailProduct->starting_price ?? 0, 0, ',', '.') }}</p>
                        </div>
                    </div>

                    @if($detailProduct->specifications && count($detailProduct->specifications) > 0)
                        <div class="pt-2">
                            <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-3 ml-1">Spesifikasi Master</h4>
                            <div class="space-y-2">
                                @foreach($detailProduct->specifications as $key => $value)
                                    <div class="flex justify-between items-center py-2.5 px-4 bg-white/60 rounded-xl border border-gray-100 shadow-sm">
                                        <span class="text-[14px] font-bold text-[#4E44DB]">{{ $key }}</span>
                                        <span class="text-[14px] font-medium text-gray-700 text-right">{{ $value }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <div class="pt-2">
                            <div class="text-[13px] text-gray-400 italic text-center py-4 bg-gray-50/50 rounded-xl border border-dashed border-gray-200">
                                Produk ini belum memiliki spesifikasi tersimpan.
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif
</div>
