<div class="bg-gray-50 min-h-screen pb-20 pt-8" x-data="{ uploading: false, progress: 0 }" x-on:livewire-upload-start="uploading = true"
    x-on:livewire-upload-finish="uploading = false; progress = 0" x-on:livewire-upload-error="uploading = false"
    x-on:livewire-upload-progress="progress = $event.detail.progress">
    <div class="max-w-3xl mx-auto px-6">
        <div class="mb-8">
            <h1 class="text-3xl font-extrabold text-gray-900">Pengajuan Tukar Tambah</h1>
            <p class="text-gray-500 mt-2">Dapatkan nilai terbaik untuk HP lama Anda, ditukar dengan <span
                    class="font-bold text-[#4E44DB]">{{ $product->name }}</span>.</p>
        </div>

        <form wire:submit="submit" class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8 space-y-6">
            {{-- Target Info --}}
            <div class="bg-[#4E44DB]/5 rounded-2xl p-5 border border-[#4E44DB]/10 flex items-center gap-4">
                <div class="w-16 h-16 rounded-xl bg-white flex items-center justify-center p-2 shrink-0">
                    <img src="{{ $product->getFirstMediaUrl('cover', 'thumb') ?: $product->getFirstMediaUrl('gallery', 'thumb') }}"
                        class="max-h-full max-w-full object-contain">
                </div>
                <div>
                    <p class="text-xs font-bold text-[#4E44DB] uppercase">Produk Incaran</p>
                    <h3 class="font-bold text-gray-900">{{ $product->name }}</h3>
                </div>
            </div>

            <hr class="border-gray-100">

            {{-- Form Fields --}}
            <div>
                <h3 class="text-lg font-bold text-gray-900 mb-4">Informasi HP Lama Anda</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Merek HP <span
                                class="text-rose-500">*</span></label>
                        <select wire:model="old_phone_brand"
                            class="w-full text-sm rounded-xl border-gray-200 px-4 py-3 focus:ring-2 focus:ring-[#4E44DB]/20 focus:border-[#4E44DB]">
                            <option value="">Pilih Merek</option>
                            @foreach ($brands as $brand)
                                <option value="{{ $brand->name }}">{{ $brand->name }}</option>
                            @endforeach
                        </select>
                        @error('old_phone_brand')
                            <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Model/Tipe <span
                                class="text-rose-500">*</span></label>
                        <input type="text" wire:model="old_phone_model"
                            class="w-full uppercase text-sm rounded-xl border-gray-200 px-4 py-3 focus:ring-2 focus:ring-[#4E44DB]/20 focus:border-[#4E44DB]"
                            placeholder="Contoh: iPhone 12 Pro Max">
                        @error('old_phone_model')
                            <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">RAM (Opsional)</label>
                        <input type="text" wire:model="old_phone_ram"
                            class="w-full text-sm rounded-xl border-gray-200 px-4 py-3 focus:ring-2 focus:ring-[#4E44DB]/20 focus:border-[#4E44DB]"
                            placeholder="Contoh: 8GB">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Penyimpanan (Opsional)</label>
                        <input type="text" wire:model="old_phone_storage"
                            class="w-full text-sm rounded-xl border-gray-200 px-4 py-3 focus:ring-2 focus:ring-[#4E44DB]/20 focus:border-[#4E44DB]"
                            placeholder="Contoh: 256GB">
                    </div>
                </div>
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Deskripsi Kondisi & Minus <span
                        class="text-rose-500">*</span></label>
                <textarea wire:model="old_phone_minus_desc" rows="4"
                    class="w-full text-sm rounded-xl border-gray-200 px-4 py-3 focus:ring-2 focus:ring-[#4E44DB]/20 focus:border-[#4E44DB]"
                    placeholder="Jelaskan secara jujur kondisi HP Anda (misal: Layar retak halus di pojok, Battery Health 82%, lecet pemakaian)"></textarea>
                @error('old_phone_minus_desc')
                    <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Foto Fisik HP (Minimal 2) <span
                        class="text-rose-500">*</span></label>
                <p class="text-xs text-gray-500 mb-3">Unggah foto tampak depan layar menyala, bagian belakang, dan
                    sisi-sisi body HP.</p>

                <input type="file" wire:model="photos" multiple accept="image/*"
                    class="block w-full text-sm text-gray-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-[#4E44DB]/10 file:text-[#4E44DB] hover:file:bg-[#4E44DB]/20 transition border border-gray-200 rounded-xl">

                <div x-show="uploading" class="w-full bg-gray-200 rounded-full h-1.5 mt-3 overflow-hidden">
                    <div class="bg-[#4E44DB] h-1.5 rounded-full transition-all duration-300"
                        x-bind:style="`width: ${progress}%`"></div>
                </div>

                @error('photos')
                    <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span>
                @enderror
                @error('photos.*')
                    <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span>
                @enderror

                @if ($photos)
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mt-4">
                        @foreach ($photos as $photo)
                            <div class="aspect-square rounded-xl overflow-hidden border border-gray-200 bg-gray-50">
                                <img src="{{ $photo->temporaryUrl() }}" class="w-full h-full object-cover">
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <div class="pt-6 border-t border-gray-100 flex justify-end gap-3">
                <a href="{{ route('products.show', $product) }}" wire:navigate
                    class="px-6 py-3.5 rounded-xl text-gray-700 font-bold hover:bg-gray-100 transition">Batal</a>
                <button type="submit"
                    class="bg-[#4E44DB] text-white px-8 py-3.5 rounded-xl font-bold hover:bg-[#3f36b8] transition shadow-lg shadow-[#4E44DB]/25 flex items-center justify-center gap-2"
                    wire:loading.attr="disabled">
                    <span wire:loading.remove wire:target="submit">Kirim Pengajuan</span>
                    <span wire:loading wire:target="submit">Memproses...</span>
                </button>
            </div>
        </form>
    </div>
</div>
