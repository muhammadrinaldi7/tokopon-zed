<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-800">Manajemen Merek (Brand)</h1>
        <button wire:click="create" class="bg-[#4E44DB] text-white px-4 py-2 rounded-xl text-sm font-semibold hover:bg-opacity-90 transition shadow-sm shadow-[#4E44DB]/20">
            Tambah Merek
        </button>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="w-full text-left text-sm whitespace-nowrap">
            <thead class="bg-gray-50 text-gray-600 font-semibold border-b border-gray-100">
                <tr>
                    <th class="px-6 py-4">Nama Merek</th>
                    <th class="px-6 py-4">Slug (URL)</th>
                    <th class="px-6 py-4">Terkait Produk</th>
                    <th class="px-6 py-4 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($brands as $brand)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 font-bold text-gray-900 flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-gray-100 border border-gray-200 flex items-center justify-center text-gray-500 overflow-hidden">
                                @if($brand->logo)
                                    <img src="{{ $brand->logo }}" alt="{{ $brand->name }}" class="w-full h-full object-cover">
                                @else
                                    <span class="font-bold text-xs">{{ substr($brand->name, 0, 1) }}</span>
                                @endif
                            </div>
                            {{ $brand->name }}
                        </td>
                        <td class="px-6 py-4 text-gray-500 font-mono text-xs">{{ $brand->slug }}</td>
                        <td class="px-6 py-4">
                            <span class="bg-gray-100 text-gray-700 font-bold px-3 py-1 rounded-lg text-xs">{{ $brand->products_count }} Item</span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <button wire:click="edit({{ $brand->id }})" class="text-gray-500 hover:text-gray-800 transition mr-3 font-semibold">
                                Edit
                            </button>
                            <button wire:click="confirmDelete({{ $brand->id }})" class="text-rose-500 hover:text-rose-700 transition font-semibold">
                                Hapus
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-12 text-center text-gray-500">Belum ada merek yang ditambahkan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        @if($brands->hasPages())
            <div class="p-4 border-t border-gray-100">
                {{ $brands->links() }}
            </div>
        @endif
    </div>

    {{-- Modal Create/Edit --}}
    @if($showModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-0 transition-opacity" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div wire:click="$set('showModal', false)" class="fixed inset-0 bg-gray-900/40 backdrop-blur-sm transition-opacity"></div>

            <div class="relative transform overflow-hidden rounded-4xl bg-white/80 backdrop-blur-2xl border border-white shadow-2xl shadow-[#4E44DB]/15 text-left transition-all sm:my-8 sm:w-full sm:max-w-md">
                
                {{-- Header --}}
                <div class="px-6 py-5 border-b border-gray-200/50 flex justify-between items-center backdrop-blur-md bg-white/40">
                    <h2 class="text-[17px] font-semibold tracking-tight text-gray-900">{{ $isEditing ? 'Ubah Merek' : 'Merek Baru' }}</h2>
                    <button wire:click="$set('showModal', false)" class="text-gray-400 hover:text-gray-600 bg-gray-100/50 hover:bg-gray-200/50 rounded-full p-1.5 transition-colors focus:outline-none">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>
                
                {{-- Form Body --}}
                <form wire:submit.prevent="store" class="p-6 space-y-5">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5 ml-1">Nama Merek / Brand</label>
                        <input type="text" wire:model="name" placeholder="Contoh: Apple" class="w-full text-[15px] bg-white/60 border border-gray-200/70 focus:bg-white focus:border-[#4E44DB] focus:ring-4 focus:ring-[#4E44DB]/10 rounded-2xl px-4 py-3 shadow-sm transition-all text-gray-800" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5 ml-1">URL Logo <span class="text-gray-400 font-normal">(opsional)</span></label>
                        <input type="text" wire:model="logo" placeholder="https://..." class="w-full text-[15px] bg-white/60 border border-gray-200/70 focus:bg-white focus:border-[#4E44DB] focus:ring-4 focus:ring-[#4E44DB]/10 rounded-2xl px-4 py-3 shadow-sm transition-all text-gray-800">
                    </div>

                    <div class="pt-4 flex gap-3">
                        <button type="button" wire:click="$set('showModal', false)" class="flex-1 bg-gray-100/50 hover:bg-gray-200/70 text-gray-700 py-3 rounded-2xl text-[15px] font-semibold transition-all">Batal</button>
                        <button type="submit" class="flex-1 bg-[#4E44DB] text-white py-3 rounded-2xl text-[15px] font-semibold hover:bg-[#3f36b8] hover:shadow-lg hover:shadow-[#4E44DB]/30 active:scale-[0.98] transition-all">
                            {{ $isEditing ? 'Simpan Perubahan' : 'Buat Merek' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
