<div class="max-w-7xl mx-auto p-2 md:p-6">
    <div class="flex gap-2 ">
        <a href="/" class="bg-neutral-500 text-white px-3 flex justify-center items-center rounded-md">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-8 rotate-180">
                <path fill-rule="evenodd"
                    d="M4.5 5.653c0-1.427 1.529-2.33 2.779-1.643l11.54 6.347c1.295.712 1.295 2.573 0 3.286L7.28 19.99c-1.25.687-2.779-.217-2.779-1.643V5.653Z"
                    clip-rule="evenodd" />
            </svg>
        </a>
        <div class="w-full flex gap-4 items-center bg-violet-600 py-3 px-6 rounded-md">
            <img src="{{ asset('assets/png/sell.png') }}" class="w-5 md:w-10 h-auto" alt="">
            <h1 class="text-white text-xl md:text-4xl font-bold">Sell Phones</h1>
        </div>
    </div>
    {{-- Header Section --}}
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 mb-10 mt-10">
        <div class="space-y-2">

            <h1 class="text-4xl md:text-5xl font-black text-neutral-900 tracking-tight">
                Jual HP <span class="text-violet-600">Instan.</span>

            </h1>
            <p class="text-neutral-500 font-medium max-w-md">Ubah gadget lamamu menjadi uang tunai dengan proses
                yang cepat dan transparan.</p>
        </div>

        <div class="hidden md:block">
            <div class="bg-white p-4 rounded-3xl shadow-sm border border-neutral-100 flex items-center gap-4">
                <div class="w-12 h-12 bg-violet-100 text-violet-600 rounded-2xl flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </div>
                <div>
                    <p class="text-[10px] font-black text-neutral-400 uppercase">Estimasi Terkini</p>
                    <p class="text-sm font-bold text-neutral-800 italic">Harga Terbaik di Pasaran</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Main Form Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">

        {{-- Left Side: Form Inputs --}}
        <div class="lg:col-span-8 space-y-8">

            {{-- Card 1: Device Info --}}
            <div
                class="bg-white rounded-[2.5rem] p-6 md:p-10 shadow-sm border border-neutral-100 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-32 h-32 bg-violet-50 rounded-bl-full opacity-50 -mr-16 -mt-16">
                </div>

                <div class="flex items-center gap-4 mb-8 relative">
                    <div
                        class="w-12 h-12 bg-violet-600 text-white rounded-2xl flex items-center justify-center font-black shadow-lg shadow-violet-200">
                        1</div>
                    <h3 class="text-xl font-black text-neutral-800">Spesifikasi Perangkat</h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 relative">
                    <div class="space-y-2">
                        <label class="text-xs font-black text-neutral-500 uppercase ml-1 tracking-wider">Merk
                            Perangkat</label>
                        <select wire:model.live="old_phone_brand"
                            class="w-full p-4 bg-neutral-50 border-2 border-transparent rounded-2xl focus:border-violet-500 focus:bg-white outline-none transition-all appearance-none cursor-pointer font-bold text-neutral-700">
                            <option value="">Pilih Merk</option>
                            @foreach ($brands as $brand)
                                <option value="{{ $brand->name }}">{{ $brand->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="space-y-2">
                        <label class="text-xs font-black text-neutral-500 uppercase ml-1 tracking-wider">Model /
                            Seri</label>
                        <input type="text" wire:model.live="old_phone_model" placeholder="Contoh: iPhone 13 Pro"
                            class="w-full p-4 bg-neutral-50 border-2 border-transparent rounded-2xl focus:border-violet-500 focus:bg-white outline-none transition-all font-bold text-neutral-700">
                    </div>

                    <div class="space-y-2">
                        <label class="text-xs font-black text-neutral-500 uppercase ml-1 tracking-wider">RAM</label>
                        <select wire:model.live="old_phone_ram"
                            class="w-full p-4 bg-neutral-50 border-2 border-transparent rounded-2xl focus:border-violet-500 focus:bg-white outline-none transition-all font-bold text-neutral-700">
                            <option value="">Pilih RAM</option>
                            @foreach (['2GB', '3GB', '4GB', '6GB', '8GB', '12GB', '16GB'] as $ram)
                                <option value="{{ $ram }}">{{ $ram }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="space-y-2">
                        <label class="text-xs font-black text-neutral-500 uppercase ml-1 tracking-wider">Internal
                            Storage</label>
                        <select wire:model.live="old_phone_storage"
                            class="w-full p-4 bg-neutral-50 border-2 border-transparent rounded-2xl focus:border-violet-500 focus:bg-white outline-none transition-all font-bold text-neutral-700">
                            <option value="">Pilih Kapasitas</option>
                            @foreach (['32GB', '64GB', '128GB', '256GB', '512GB', '1TB'] as $rom)
                                <option value="{{ $rom }}">{{ $rom }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- Battery Health (Apple Special) --}}
                @if ($old_phone_brand === 'APPLE')
                    <div
                        class="mt-8 p-6 bg-violet-50 border-2 border-violet-100 rounded-[2rem] animate-in zoom-in duration-300">
                        <div class="flex items-center gap-3 mb-5">
                            <div class="p-2 bg-violet-600 rounded-lg text-white">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path d="M13 10V3L4 14h7v7l9-11h-7z" stroke-width="2.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                </svg>
                            </div>
                            <label class="text-sm font-black text-violet-900 uppercase">Kesehatan Baterai
                                (BH)</label>
                        </div>

                        <div class="grid grid-cols-3 gap-3">
                            @foreach (['95', '90', '85'] as $bh)
                                <label class="relative cursor-pointer group">
                                    <input type="radio" wire:model="old_phone_battery_health"
                                        value="{{ $bh }}" class="peer hidden">
                                    <div
                                        class="p-4 bg-white border-2 border-transparent rounded-2xl text-center transition-all peer-checked:border-violet-600 peer-checked:bg-violet-600 peer-checked:text-white hover:border-violet-200 shadow-sm">
                                        <span class="block text-lg font-black">{{ $bh }}%</span>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                        <input type="number" wire:model="old_phone_battery_health"
                            placeholder="Atau ketik angka spesifik..."
                            class="w-full mt-4 p-3 bg-white/50 border-2 border-dashed border-violet-200 rounded-xl text-center text-sm font-bold text-violet-700 focus:border-violet-600 focus:bg-white outline-none transition-all">
                    </div>
                @endif
            </div>

            {{-- Card 2: Condition & Photos --}}
            <div class="bg-white rounded-[2.5rem] p-6 md:p-10 shadow-sm border border-neutral-100">
                <div class="flex items-center gap-4 mb-8">
                    <div
                        class="w-12 h-12 bg-violet-600 text-white rounded-2xl flex items-center justify-center font-black shadow-lg shadow-violet-200">
                        2</div>
                    <h3 class="text-xl font-black text-neutral-800">Kondisi & Kelengkapan</h3>
                </div>

                <div class="space-y-6">
                    <div class="space-y-3">
                        <label class="text-xs font-black text-neutral-500 uppercase ml-1">Kondisi Fisik</label>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                            @foreach (['Mulus', 'Lecet', 'Jamuran', 'Retak'] as $cond)
                                <label class="cursor-pointer">
                                    <input type="radio" wire:model="old_phone_condition" value="{{ $cond }}"
                                        class="peer hidden">
                                    <div
                                        class="py-3 px-2 border-2 border-neutral-50 bg-neutral-50 rounded-2xl text-center text-sm font-bold text-neutral-600 transition-all peer-checked:border-violet-600 peer-checked:bg-violet-50 peer-checked:text-violet-700">
                                        {{ $cond }}
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="space-y-3">
                        <label class="text-xs font-black text-neutral-500 uppercase ml-1">Kelengkapan</label>
                        <div class="flex flex-wrap gap-2">
                            @foreach (['Kotak (Box)', 'Charger Ori', 'Handsfree', 'Nota Beli'] as $set)
                                <label class="cursor-pointer">
                                    <input type="checkbox" wire:model="old_phone_sets" value="{{ $set }}"
                                        class="peer hidden">
                                    <div
                                        class="px-5 py-2.5 rounded-full border-2 border-neutral-100 text-xs font-bold text-neutral-500 transition-all peer-checked:bg-neutral-800 peer-checked:text-white peer-checked:border-neutral-800">
                                        {{ $set }}
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="space-y-3">
                        <label class="text-xs font-black text-neutral-500 uppercase ml-1">Catatan Tambahan (Minus
                            dll)</label>
                        <textarea wire:model="old_phone_additional_note" rows="3"
                            placeholder="Jelaskan kondisi detail jika ada minus..."
                            class="w-full p-4 bg-neutral-50 border-2 border-transparent rounded-3xl focus:border-violet-500 focus:bg-white outline-none transition-all font-medium text-neutral-700"></textarea>
                    </div>

                    <div class="space-y-3 pt-4">
                        <label class="text-xs font-black text-neutral-500 uppercase ml-1">Upload Foto HP (Maks.
                            5MB/Foto)</label>

                        <div class="relative group">
                            <input type="file" wire:model="photos" multiple accept="image/*"
                                class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                            <div
                                class="w-full p-8 border-2 border-dashed border-violet-200 rounded-3xl bg-violet-50/50 group-hover:bg-violet-50 transition-colors flex flex-col items-center justify-center text-center">
                                <div
                                    class="w-12 h-12 bg-violet-100 text-violet-600 rounded-2xl flex items-center justify-center mb-3">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12">
                                        </path>
                                    </svg>
                                </div>
                                <p class="font-bold text-violet-900 text-sm">Klik atau seret foto ke sini</p>
                                <p class="text-xs text-violet-600/70 mt-1">Bisa pilih lebih dari satu foto
                                    sekaligus</p>
                            </div>
                        </div>

                        <div wire:loading wire:target="photos"
                            class="text-xs font-bold text-violet-600 mt-2 flex items-center gap-2">
                            <svg class="animate-spin h-4 w-4 text-violet-600" xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10"
                                    stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                </path>
                            </svg>
                            Sedang mengunggah...
                        </div>

                        @if ($photos)
                            <div class="grid grid-cols-3 md:grid-cols-4 gap-3 mt-4">
                                @foreach ($photos as $photo)
                                    <div
                                        class="relative aspect-square rounded-2xl overflow-hidden border border-neutral-200 shadow-sm">
                                        <img src="{{ $photo->temporaryUrl() }}" class="w-full h-full object-cover">
                                    </div>
                                @endforeach
                            </div>
                        @endif
                        @error('photos.*')
                            <span class="text-xs text-rose-500 font-bold block mt-1">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        {{-- Right Side: Summary Sticky --}}
        <div class="lg:col-span-4">
            <div class="sticky top-20 space-y-4">
                <div class="bg-white text-neutral-900 rounded-xl p-8 shadow-md relative overflow-hidden">
                    <h4 class="text-violet-500 font-bold uppercase tracking-widest text-xs mb-4 relative z-10">
                        Ringkasan Unit
                        <span class="w-1.5 h-1.5 bg-violet-500 rounded-full animate-pulse"></span>
                    </h4>

                    <div class="space-y-5 relative">
                        <div class="flex flex-col gap-1 border-b border-neutral-200 pb-4">
                            <span class="text-[10px] font-black text-neutral-400 uppercase tracking-widest">Model
                                Perangkat</span>
                            <span class="text-lg font-bold italic">{{ $old_phone_model ?: 'Belum diisi' }}</span>
                        </div>

                        <div class="grid grid-cols-2 gap-4 border-b border-neutral-200 pb-4">
                            <div class="flex flex-col gap-1">
                                <span
                                    class="text-[10px] font-black text-neutral-400 uppercase tracking-widest">Brand</span>
                                <span class="font-bold text-violet-400">{{ $old_phone_brand ?: '-' }}</span>
                            </div>
                            <div class="flex flex-col gap-1">
                                <span
                                    class="text-[10px] font-black text-neutral-400 uppercase tracking-widest">Storage</span>
                                <span class="font-bold">{{ $old_phone_storage ?: '-' }}</span>
                            </div>
                        </div>

                        <div class="pt-4">
                            <button type="button" wire:click="submit"
                                class="w-full bg-violet-600 hover:bg-violet-700 text-white py-5 rounded-2xl font-black text-lg transition-all active:scale-[0.97] shadow-lg shadow-violet-900/20">
                                Kirim Penawaran
                            </button>
                            <p class="text-center text-[10px] text-neutral-400 mt-4 italic font-medium">
                                Dengan mengirim, Anda setuju dengan proses pengecekan teknis oleh tim kami.
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Help Box --}}
                <div class="bg-white rounded-3xl p-6 border border-neutral-100 flex items-center gap-4">
                    <div class="w-10 h-10 bg-emerald-100 text-emerald-600 rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </div>
                    <div class="text-xs">
                        <p class="font-black text-neutral-800">Butuh bantuan?</p>
                        <a href="#" class="text-violet-600 font-bold hover:underline">Hubungi via
                            WhatsApp</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
