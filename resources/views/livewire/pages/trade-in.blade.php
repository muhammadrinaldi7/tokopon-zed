<section class="max-w-6xl mx-auto p-4 md:p-10">
    {{-- Tambahkan wire:submit agar tombol kirim berfungsi --}}
    <form wire:submit.prevent="submit">
        <div class="flex flex-col md:flex-row md:items-end justify-between mb-10 gap-4">
            <div>
                <a href="/" class="inline-flex items-center text-emerald-600 font-semibold mb-2 group"
                    wire:navigate>
                    <svg class="w-5 h-5 mr-1 transform group-hover:-translate-x-1 transition-transform" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    Kembali
                </a>
                <h1 class="text-4xl md:text-6xl font-black tracking-tighter text-neutral-800">
                    Trade-In <span class="text-emerald-500">Program</span>
                </h1>
                <p class="text-neutral-500 text-lg mt-2">Tukarkan HP lama kamu dengan penawaran harga terbaik.</p>
            </div>

            <div
                class="bg-emerald-100 text-emerald-700 px-4 py-2 rounded-2xl text-sm font-bold flex items-center h-fit border border-emerald-200">
                <span class="relative flex h-3 w-3 mr-2">
                    <span
                        class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-3 w-3 bg-emerald-500"></span>
                </span>
                Proses Cepat 15 Menit
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            <div class="lg:col-span-8 space-y-6">

                <div class="bg-white rounded-3xl p-6 shadow-sm border border-neutral-100">
                    <div class="flex items-center mb-6">
                        <div
                            class="w-10 h-10 bg-emerald-500 text-white rounded-xl flex items-center justify-center font-bold shadow-lg shadow-emerald-200">
                            1</div>
                        <h3 class="text-xl font-bold ml-4 text-neutral-800">Informasi HP Lama</h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="space-y-2">
                            <label class="text-sm font-bold text-neutral-700 ml-1">Merk HP</label>

                            <div class="relative">
                                <select wire:model="old_phone_brand"
                                    class="w-full p-4 bg-neutral-50 border-2 border-transparent rounded-2xl focus:border-emerald-500 focus:bg-white outline-none transition-all appearance-none cursor-pointer">
                                    <option value="">Pilih Merk HP</option>

                                    {{-- Looping data brands dari database --}}
                                    @foreach ($brands as $brand)
                                        <option value="{{ $brand->name }}">{{ $brand->name }}</option>
                                    @endforeach

                                    <option value="Lainnya">Lainnya</option>
                                </select>

                                {{-- Icon Panah Bawah agar select terlihat lebih bagus --}}
                                <div
                                    class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-neutral-500">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7" />
                                    </svg>
                                </div>
                            </div>

                            @error('old_phone_brand')
                                <span class="text-red-500 text-xs ml-1">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-bold text-neutral-700 ml-1">Model / Seri</label>
                            <input type="text" wire:model="old_phone_model" placeholder="Contoh: iPhone 12 Pro Max"
                                class="w-full p-4 bg-neutral-50 border-2 border-transparent rounded-2xl focus:border-emerald-500 focus:bg-white outline-none transition-all">
                            @error('old_phone_model')
                                <span class="text-red-500 text-xs ml-1">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-bold text-neutral-700 ml-1">RAM</label>
                            <input type="text" wire:model="old_phone_ram" placeholder="Contoh: 6GB"
                                class="w-full p-4 bg-neutral-50 border-2 border-transparent rounded-2xl focus:border-emerald-500 focus:bg-white outline-none transition-all">
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-bold text-neutral-700 ml-1">Kapasitas (Storage)</label>
                            <input type="text" wire:model="old_phone_storage" placeholder="Contoh: 128GB"
                                class="w-full p-4 bg-neutral-50 border-2 border-transparent rounded-2xl focus:border-emerald-500 focus:bg-white outline-none transition-all">
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-3xl p-6 shadow-sm border border-neutral-100">
                    <div class="flex items-center mb-6">
                        <div
                            class="w-10 h-10 bg-emerald-500 text-white rounded-xl flex items-center justify-center font-bold shadow-lg shadow-emerald-200">
                            2</div>
                        <h3 class="text-xl font-bold ml-4 text-neutral-800">Bagaimana Kondisi HP-nya?</h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        @php
                            $conditions = [
                                ['title' => 'Mulus', 'desc' => 'Seperti baru, tidak ada lecet'],
                                ['title' => 'Lecet Wajar', 'desc' => 'Ada goresan halus di body'],
                                ['title' => 'Minus', 'desc' => 'Layar retak / fungsi error'],
                            ];
                        @endphp
                        @foreach ($conditions as $cond)
                            <label class="relative cursor-pointer group">
                                <input type="radio" wire:model="old_phone_condition" value="{{ $cond['title'] }}"
                                    class="peer hidden">
                                <div
                                    class="h-full p-4 border-2 border-neutral-100 rounded-2xl transition-all peer-checked:border-emerald-500 peer-checked:bg-emerald-50 group-hover:border-emerald-200">
                                    <p class="font-bold text-neutral-800">{{ $cond['title'] }}</p>
                                    <p class="text-xs text-neutral-500 mt-1 leading-tight">{{ $cond['desc'] }}</p>
                                </div>
                                <div
                                    class="absolute top-3 right-3 opacity-0 peer-checked:opacity-100 transition-opacity">
                                    <svg class="w-5 h-5 text-emerald-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                            </label>
                        @endforeach
                    </div>
                </div>

                <div class="bg-white rounded-3xl p-6 shadow-sm border border-neutral-100">
                    <div class="flex items-center mb-6">
                        <div
                            class="w-10 h-10 bg-emerald-500 text-white rounded-xl flex items-center justify-center font-bold shadow-lg shadow-emerald-200">
                            3</div>
                        <h3 class="text-lg font-bold ml-4 text-neutral-800">Kelengkapan & Catatan</h3>
                    </div>

                    <div class="flex flex-wrap gap-3 mb-6">
                        @foreach (['Kotak (Box)', 'Charger Original', 'Nota Pembelian'] as $item)
                            <label
                                class="flex items-center px-4 py-2 bg-neutral-100 rounded-full cursor-pointer hover:bg-neutral-200 transition-colors">
                                <input type="checkbox" wire:model="old_phone_sets" value="{{ $item }}"
                                    class="rounded text-emerald-500 focus:ring-emerald-500 mr-2">
                                <span class="text-sm font-medium text-neutral-700">{{ $item }}</span>
                            </label>
                        @endforeach
                    </div>

                    <textarea wire:model="old_phone_additional_note" rows="3"
                        placeholder="Tulis catatan tambahan jika ada minus lain..."
                        class="w-full p-4 bg-neutral-50 border-2 border-transparent rounded-2xl focus:border-emerald-500 focus:bg-white outline-none transition-all"></textarea>
                </div>

                <div class="bg-white rounded-3xl p-6 shadow-sm border border-neutral-100">
                    <div class="flex items-center mb-4">
                        <div
                            class="w-10 h-10 bg-emerald-500 text-white rounded-xl flex items-center justify-center font-bold shadow-lg shadow-emerald-200">
                            4</div>
                        <h3 class="text-xl font-bold ml-4 text-neutral-800">Upload Foto Unit</h3>
                    </div>
                    <p class="text-xs text-neutral-500 mb-4 ml-14 italic">*Upload minimal 2 foto (Depan & Belakang)</p>

                    <div class="ml-14">
                        <input type="file" wire:model="photos" multiple
                            class="text-sm text-neutral-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100">

                        <div class="flex flex-wrap gap-2 mt-4">
                            @if ($photos)
                                @foreach ($photos as $photo)
                                    <img src="{{ $photo->temporaryUrl() }}"
                                        class="w-20 h-20 object-cover rounded-xl border">
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-3xl p-6 shadow-sm border border-neutral-100">
                    <div class="flex items-center mb-6">
                        <div
                            class="w-10 h-10 bg-emerald-500 text-white rounded-xl flex items-center justify-center font-bold shadow-lg shadow-emerald-200">
                            5</div>
                        <h3 class="text-xl font-bold ml-4 text-neutral-800">Pilih HP Incaranmu</h3>
                    </div>

                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                        @foreach ($products as $item)
                            <label class="group relative cursor-pointer">
                                <input type="radio" name="target_phone" value="{{ $item->id }}"
                                    wire:model.live="selectedProductId" class="peer hidden">
                                <div
                                    class="h-full border-2 border-neutral-100 rounded-3xl p-4 transition-all duration-300 peer-checked:border-emerald-500 peer-checked:bg-emerald-50 group-hover:shadow-lg group-hover:shadow-emerald-100">
                                    <div
                                        class="aspect-square bg-neutral-50 rounded-2xl mb-4 overflow-hidden flex items-center justify-center p-4 transition-all group-hover:scale-105">
                                        <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->name }}"
                                            class="w-full h-auto object-contain drop-shadow-sm">
                                    </div>
                                    <div class="space-y-1">
                                        <h4 class="font-bold text-neutral-800 text-sm leading-tight">
                                            {{ $item->name }}</h4>
                                        <p class="text-emerald-600 font-extrabold text-xs">Rp
                                            {{ number_format($item->price, 0, ',', '.') }}</p>
                                    </div>
                                    <div
                                        class="absolute top-3 right-3 w-6 h-6 bg-emerald-500 rounded-full flex items-center justify-center text-white scale-0 transition-transform duration-300 peer-checked:scale-100">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path d="M5 13l4 4L19 7" stroke-width="3" stroke-linecap="round"
                                                stroke-linejoin="round" />
                                        </svg>
                                    </div>
                                </div>
                            </label>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="lg:col-span-4">
                <div
                    class="bg-emerald-600 rounded-[2.5rem] p-8 text-white sticky top-10 shadow-2xl shadow-emerald-200 overflow-hidden">
                    <div class="absolute -top-10 -right-10 w-32 h-32 bg-emerald-400 rounded-full blur-3xl opacity-50">
                    </div>

                    <h4 class="text-emerald-200 font-bold uppercase tracking-widest text-xs mb-4 relative z-10">
                        Ringkasan</h4>

                    <div class="space-y-4 border-b border-emerald-500/50 pb-6 mb-6 relative z-10">
                        <div class="flex justify-between text-sm opacity-90">
                            <span>HP Incaran</span>
                            <span
                                class="font-bold text-right italic">{{ $selectedProductId ? $products->find($selectedProductId)->name : 'Belum memilih' }}</span>
                        </div>
                    </div>

                    <ul class="space-y-4 mb-8 text-xs relative z-10">
                        <li class="flex items-start">
                            <svg class="w-4 h-4 mr-2 text-emerald-300 shrink-0" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path d="M5 13l4 4L19 7" stroke-width="3" stroke-linecap="round"
                                    stroke-linejoin="round" />
                            </svg>
                            <span>Proses penaksiran harga akan dilakukan oleh tim kami setelah form dikirim.</span>
                        </li>
                    </ul>

                    <button type="submit" wire:loading.attr="disabled"
                        class="w-full bg-white text-emerald-600 font-black py-4 rounded-2xl shadow-xl hover:bg-emerald-50 transition-all active:scale-95 flex items-center justify-center gap-2 relative z-10 group">
                        <span wire:loading.remove>Kirim Pengajuan</span>
                        <span wire:loading>Memproses...</span>
                        <svg wire:loading.remove class="w-5 h-5 group-hover:translate-x-1 transition-transform"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 7l5 5m0 0l-5 5m5-5H6" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </form>
</section>
