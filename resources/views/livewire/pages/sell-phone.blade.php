<div class="max-w-7xl mx-auto p-2  md:p-6 min-h-screen" x-data="{ step: 1 }" x-cloak>
    {{-- Header Navigation --}}
    <div class="flex gap-2">
        <a href="/"
            class="bg-neutral-500 hover:bg-neutral-600 transition-colors text-white px-3 flex justify-center items-center rounded-md">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-8 rotate-180">
                <path fill-rule="evenodd"
                    d="M4.5 5.653c0-1.427 1.529-2.33 2.779-1.643l11.54 6.347c1.295.712 1.295 2.573 0 3.286L7.28 19.99c-1.25.687-2.779-.217-2.779-1.643V5.653Z"
                    clip-rule="evenodd" />
            </svg>
        </a>
        <div class="w-full flex gap-4 items-center bg-violet-600 py-3 px-6 rounded-md shadow-sm">
            <img src="{{ asset('assets/png/sell.png') }}" class="w-5 md:w-10 h-auto" alt="">
            <h1 class="text-white text-xl md:text-4xl font-bold">Sell Phones</h1>
        </div>
    </div>

    {{-- Title Section --}}
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 mb-8 mt-10 text-left">
        <div class="space-y-2 mx-auto md:mx-0">
            <h1 class="text-3xl md:text-5xl font-black text-neutral-900 tracking-tight">
                Jual HP <span class="text-violet-600">Instan.</span>
            </h1>
            <p class="text-neutral-500 text-sm md:text-base  font-medium max-w-md">Ubah gadget lamamu menjadi uang tunai
                dengan proses yang
                cepat dan transparan.</p>
        </div>
    </div>

    {{-- Stepper Indicator (Visual Progress) - Violet Version --}}
    <div class="mb-14 w-full max-w-7xl mx-auto  mt-4">
        <div class="flex justify-between items-start relative">
            <!-- Progress Line Background -->
            {{-- Posisi top disetel 20px agar tepat memotong tengah lingkaran --}}
            <div
                class="absolute left-0 top-[20px] transform -translate-y-1/2 w-full h-1 bg-neutral-200 rounded-full z-0">
            </div>

            <!-- Progress Line Active -->
            <div class="absolute left-0 top-[20px] transform -translate-y-1/2 h-1 bg-violet-600 rounded-full z-0 transition-all duration-500 ease-in-out"
                :style="'width: ' + ((step - 1) * 50) + '%'"></div>

            <!-- Step 1 Dot -->
            <div class="relative z-10 flex flex-col items-center cursor-pointer group" @click="step = 1">
                <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-sm transition-all duration-300 shrink-0"
                    :class="step >= 1 ? 'bg-violet-600 text-white shadow-lg shadow-violet-200 ring-4 ring-violet-50' :
                        'bg-white text-neutral-400 border-2 border-neutral-200'">
                    1
                </div>
                {{-- Teks sekarang fleksibel dan wrap otomatis --}}
                <span
                    class="mt-3 text-[10px] md:text-xs font-bold text-center leading-tight transition-colors duration-300 w-auto"
                    :class="step >= 1 ? 'text-violet-700' : 'text-neutral-400'">
                    Spesifikasi
                </span>
            </div>

            <!-- Step 2 Dot -->
            <div class="relative z-10 flex flex-col items-center cursor-pointer group" @click="if(step > 2) step = 2">
                <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-sm transition-all duration-300 shrink-0"
                    :class="step >= 2 ? 'bg-violet-600 text-white shadow-lg shadow-violet-200 ring-4 ring-violet-50' :
                        'bg-white text-neutral-400 border-2 border-neutral-200'">
                    2
                </div>
                <span
                    class="mt-3 text-[10px] md:text-xs font-bold text-center leading-tight transition-colors duration-300 w-auto"
                    :class="step >= 2 ? 'text-violet-700' : 'text-neutral-400'">
                    Kondisi
                </span>
            </div>

            <!-- Step 3 Dot -->
            <div class="relative z-10 flex flex-col items-center group">
                <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-sm transition-all duration-300 shrink-0"
                    :class="step >= 3 ? 'bg-violet-600 text-white shadow-lg shadow-violet-200 ring-4 ring-violet-50' :
                        'bg-white text-neutral-400 border-2 border-neutral-200'">
                    3
                </div>
                <span
                    class="mt-3 text-[10px] md:text-xs font-bold text-center leading-tight transition-colors duration-300 w-sauto"
                    :class="step >= 3 ? 'text-violet-700' : 'text-neutral-400'">
                    Ringkasan
                </span>
            </div>
        </div>
    </div>

    {{-- Form Steps Container --}}
    <div class="mt-16 max-w-7xl mx-auto">

        {{-- STEP 1: Device Info --}}
        <div x-show="step === 1" x-transition:enter="transition ease-out duration-300 delay-100"
            x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0"
            class="space-y-8">

            {{-- Brand Selection Cards (with Logo) --}}
            <div>
                <h1 class="text-xs font-black text-neutral-500 uppercase ml-1 tracking-wider mb-4 block">Pilih Merk
                    Perangkat</h1>
                {{-- Menggunakan grid 3 kolom di mobile, dan 4/5 kolom di layar besar agar card logonya pas --}}
                {{-- 1. x-data dipindah ke pembungkus utama agar animasinya barengan --}}
                <div class="grid grid-cols-2 md:grid-cols-4 gap-3 md:gap-4" x-data="{ show: false }"
                    x-init="setTimeout(() => show = true, 100)">

                    @foreach ($brands as $brand)
                        <label class="relative cursor-pointer group">
                            <input type="radio" wire:model.live="old_phone_brand" value="{{ $brand->name }}"
                                class="peer hidden">
                            <div
                                class="bg-white h-auto overflow-hidden rounded-lg text-center transition-all peer-checked:bg-violet-100 hover:shadow-lg shadow-md flex items-center justify-center">

                                @php
                                    // Tentukan nama dasar dulu (iphone atau nama brand)
                                    $baseName =
                                        strtolower($brand->name) === 'apple' ? 'iphone' : strtolower($brand->name);

                                    // Tambahkan kata 'header' di belakangnya
                                    $imageName = $baseName . 'header';
                                @endphp

                                {{-- PERBAIKAN ANIMASI DI SINI --}}
                                <img x-show="show" x-cloak
                                    x-transition:enter="transition transform ease-out duration-1000 delay-500"
                                    x-transition:enter-start="opacity-0 translate-y-full"
                                    x-transition:enter-end="opacity-100 translate-y-0"
                                    src="{{ asset('assets/brand/' . $imageName . '.png') }}" alt="{{ $brand->name }}"
                                    class="object-contain">

                            </div>
                        </label>
                    @endforeach
                </div>
                @error('old_phone_brand')
                    <span class="text-xs text-rose-500 font-bold block mt-1">{{ $message }}</span>
                @enderror
            </div>

            {{-- Detail Inputs (Hanya muncul jika brand sudah dipilih) --}}
            <div x-show="$wire.old_phone_brand" x-cloak x-transition:enter="transition ease-out duration-500"
                x-transition:enter-start="opacity-0 -translate-y-4" x-transition:enter-end="opacity-100 translate-y-0"
                class="space-y-6">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2 md:col-span-2">
                        <label class="text-xs font-black text-neutral-500 uppercase ml-1 tracking-wider">Model /
                            Seri</label>
                        <input type="text" wire:model.live="old_phone_model" placeholder="Contoh: iPhone 13 Pro"
                            class="w-full p-4 bg-white shadow-sm border-2 border-transparent rounded-2xl focus:border-violet-500 outline-none transition-all font-bold text-neutral-700">
                        @error('old_phone_model')
                            <span class="text-xs text-rose-500 font-bold block mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Untuk RAM, kita gunakan x-show agar tidak dihapus dari DOM saat pindah ke Apple --}}
                    <div x-show="$wire.old_phone_brand && $wire.old_phone_brand.toLowerCase() !== 'apple'" x-cloak
                        class="space-y-2">
                        <label class="text-xs font-black text-neutral-500 uppercase ml-1 tracking-wider">RAM</label>
                        <select wire:model.live="old_phone_ram"
                            class="w-full p-4 bg-white shadow-sm border-2 border-transparent rounded-2xl focus:border-violet-500 outline-none transition-all font-bold text-neutral-700 appearance-none cursor-pointer">
                            <option value="">Pilih RAM</option>
                            @foreach (['2GB', '3GB', '4GB', '6GB', '8GB', '12GB', '16GB'] as $ram)
                                <option value="{{ $ram }}">{{ $ram }}</option>
                            @endforeach
                        </select>
                        @error('old_phone_ram')
                            <span class="text-xs text-rose-500 font-bold block mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="space-y-2">
                        <h1 class="text-xs font-black text-neutral-500 uppercase ml-1 tracking-wider">Internal Storage
                        </h1>
                        <select wire:model.live="old_phone_storage"
                            class="w-full p-4 bg-white shadow-sm border-2 border-transparent rounded-2xl focus:border-violet-500 outline-none transition-all font-bold text-neutral-700 appearance-none cursor-pointer">
                            <option value="">Pilih Kapasitas</option>
                            @foreach (['32GB', '64GB', '128GB', '256GB', '512GB', '1TB'] as $rom)
                                <option value="{{ $rom }}">{{ $rom }}</option>
                            @endforeach
                        </select>
                        @error('old_phone_storage')
                            <span class="text-xs text-rose-500 font-bold block mt-1">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                {{-- Battery Health (Apple Special) menggunakan x-show --}}
                <div x-show="$wire.old_phone_brand && $wire.old_phone_brand.toLowerCase() === 'apple'" x-cloak
                    x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95"
                    x-transition:enter-end="opacity-100 scale-100"
                    class="mt-4 p-6 bg-violet-50 border-2 border-violet-100 rounded-3xl">

                    <div class="flex items-center gap-3 mb-5">
                        <div class="p-2 bg-violet-600 rounded-lg text-white">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path d="M13 10V3L4 14h7v7l9-11h-7z" stroke-width="2.5" stroke-linecap="round"
                                    stroke-linejoin="round" />
                            </svg>
                        </div>
                        <h1 class="text-sm font-black text-violet-900 uppercase">Kesehatan Baterai (BH)</h1>
                    </div>

                    <div class="grid grid-cols-3 gap-3">
                        @foreach (['95', '90', '85'] as $bh)
                            <label class="relative cursor-pointer group">
                                <input type="radio" wire:model.live="old_phone_battery_health"
                                    value="{{ $bh }}" class="peer hidden">
                                <div
                                    class="p-4 bg-white border-2 border-transparent rounded-2xl text-center transition-all peer-checked:border-violet-600 peer-checked:bg-violet-600 peer-checked:text-white hover:border-violet-200 shadow-sm">
                                    <span class="block text-lg font-black">{{ $bh }}%</span>
                                </div>
                            </label>
                        @endforeach
                    </div>

                    <input type="number" wire:model.live="old_phone_battery_health"
                        placeholder="Atau ketik angka spesifik..."
                        class="w-full mt-4 p-3 bg-white/60 border-2 border-dashed border-violet-200 rounded-xl text-center text-sm font-bold text-violet-700 focus:border-violet-600 focus:bg-white outline-none transition-all">

                    @error('old_phone_battery_health')
                        <span class="text-xs text-rose-500 font-bold block mt-1">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            {{-- Evaluasi Validasi Step 1 --}}
            @php
                $isStep1Valid = false;

                // Pastikan input dasar sudah terisi
                if ($old_phone_brand && $old_phone_model && $old_phone_storage) {
                    if (strtolower($old_phone_brand) === 'apple') {
                        // Jika Apple, Battery Health harus terisi
                        $isStep1Valid = !empty($old_phone_battery_health);
                    } else {
                        // Jika bukan Apple, RAM harus terisi
                        $isStep1Valid = !empty($old_phone_ram);
                    }
                }
            @endphp
            <div class="flex justify-end pt-4 pb-10">
                <button type="button" @click="step = 2" {{-- Tambahkan attribute disabled agar benar-benar tidak bisa diklik --}} {{ $isStep1Valid ? '' : 'disabled' }}
                    class="px-8 py-4 rounded-2xl font-black transition-all flex items-center gap-2 shadow-lg active:scale-95
                    {{ $isStep1Valid ? 'bg-violet-600 hover:bg-violet-700 text-white shadow-violet-900/20' : 'bg-neutral-200 text-neutral-400 cursor-not-allowed pointer-events-none' }}">
                    Lanjut Kondisi Fisik
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                    </svg>
                </button>
            </div>
        </div>

        {{-- STEP 2: Condition & Photos --}}
        <div x-show="step === 2" x-transition:enter="transition ease-out duration-300 delay-100"
            x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0"
            style="display: none;" class="space-y-8">

            <h3 class="text-lg md:text-xl uppercase font-black text-neutral-800 px-1">Kondisi & Kelengkapan</h3>

            <div class="space-y-8">
                {{-- Kondisi Fisik --}}
                <div class="space-y-3">
                    <h1 class="text-xs font-black text-neutral-500 uppercase ml-1 tracking-wider block">Kondisi
                        Fisik</h1>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                        @foreach (['Mulus', 'Lecet', 'Retak'] as $cond)
                            <label class="cursor-pointer group">
                                <input type="radio" wire:model.live="old_phone_condition"
                                    value="{{ $cond }}" class="peer hidden">
                                <div
                                    class="py-4 px-2 border-2 border-transparent bg-white shadow-sm rounded-2xl text-center text-sm font-bold text-neutral-600 transition-all peer-checked:border-violet-600 peer-checked:bg-violet-50 peer-checked:text-violet-700 hover:border-violet-200">
                                    {{ $cond }}
                                </div>
                            </label>
                        @endforeach
                    </div>
                    @error('old_phone_condition')
                        <span class="text-xs text-rose-500 font-bold block mt-1">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Kelengkapan --}}
                <div class="space-y-3">
                    <h1 class="text-xs font-black text-neutral-500 uppercase ml-1 tracking-wider block">Kelengkapan
                    </h1>
                    <div class="flex flex-wrap gap-2">
                        @foreach (['Kotak (Box)', 'Charger Ori', 'Nota Beli'] as $set)
                            <label class="cursor-pointer">
                                {{-- Menggunakan wire:model.live agar bisa dievaluasi untuk validasi step 2 --}}
                                <input type="checkbox" wire:model.live="old_phone_sets" value="{{ $set }}"
                                    class="peer hidden">
                                <div
                                    class="px-5 py-3 rounded-full bg-white shadow-sm border-2 border-transparent text-xs font-bold text-neutral-500 transition-all peer-checked:bg-neutral-800 peer-checked:text-white hover:border-neutral-300">
                                    {{ $set }}
                                </div>
                            </label>
                        @endforeach
                    </div>
                    @error('old_phone_sets')
                        <span class="text-xs text-rose-500 font-bold block mt-1">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Catatan Tambahan --}}
                <div class="space-y-3">
                    <h1 class="text-xs font-black text-neutral-500 uppercase ml-1 tracking-wider block">Catatan
                        Tambahan (Minus dll)</h1>
                    <textarea wire:model.live="old_phone_additional_note" rows="3"
                        placeholder="Jelaskan kondisi detail jika ada minus..."
                        class="w-full p-4 bg-white shadow-sm border-2 border-transparent rounded-2xl focus:border-violet-500 outline-none transition-all font-medium text-neutral-700"></textarea>
                </div>

                {{-- Upload Foto --}}
                <div class="space-y-3">
                    <h1 class="text-xs font-black text-neutral-500 uppercase ml-1 tracking-wider block">Upload Foto
                        HP (Maks. 5MB/Foto)</h1>
                    <div class="relative group">
                        {{-- Menggunakan wire:model.live agar bisa mendeteksi saat file dipilih --}}
                        <input type="file" wire:model.live="photos" multiple accept="image/*"
                            class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                        <div
                            class="w-full p-8 border-2 border-dashed border-violet-200 rounded-3xl bg-white shadow-sm hover:bg-violet-50 transition-colors flex flex-col items-center justify-center text-center">
                            <div
                                class="w-12 h-12 bg-violet-100 text-violet-600 rounded-2xl flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                                </svg>
                            </div>
                            <p class="font-bold text-violet-900 text-sm">Klik atau seret foto ke sini</p>
                            <p class="text-xs text-violet-600/70 mt-1">Minimal 1, Maksimal 5 foto</p>
                        </div>
                        @error('photos')
                            <span class="text-xs text-rose-500 font-bold block mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <div wire:loading wire:target="photos"
                        class="text-xs font-bold text-violet-600 mt-2 flex items-center gap-2">
                        <svg class="animate-spin h-4 w-4 text-violet-600" xmlns="http://www.w3.org/2000/svg"
                            fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                stroke-width="4"></circle>
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

            {{-- Evaluasi Validasi Step 2 --}}
            @php
                $isStep2Valid = false;

                // Pastikan kondisi fisik, minimal 1 kelengkapan, dan foto terisi
                if ($old_phone_condition && !empty($old_phone_sets) && !empty($photos)) {
                    $isStep2Valid = true;
                }
            @endphp

            <div class="flex justify-between items-center pt-4 pb-10">
                <button type="button" @click="step = 1"
                    class="text-neutral-500 hover:text-neutral-800 font-bold px-6 py-4 transition-colors flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Kembali
                </button>
                <button type="button" @click="step = 3" {{ $isStep2Valid ? '' : 'disabled' }}
                    class="px-8 py-4 rounded-2xl font-black transition-all flex items-center gap-2 shadow-lg active:scale-95
                    {{ $isStep2Valid ? 'bg-violet-600 hover:bg-violet-700 text-white shadow-violet-900/20' : 'bg-neutral-200 text-neutral-400 cursor-not-allowed pointer-events-none' }}">
                    Cek Ringkasan
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                    </svg>
                </button>
            </div>
        </div>

        {{-- STEP 3: Summary & Submit --}}
        <div x-show="step === 3" x-transition:enter="transition ease-out duration-300 delay-100"
            x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0"
            style="display: none;" class="space-y-6">
            <div
                class="bg-white text-neutral-900 rounded-[2.5rem] p-8 md:p-10 shadow-sm border border-neutral-100 relative overflow-hidden">
                <h4
                    class="text-violet-500 font-bold uppercase tracking-widest text-xs mb-6 relative z-10 flex items-center gap-2">
                    Ringkasan Unit Anda
                    <span class="w-2 h-2 bg-emerald-500 rounded-full animate-pulse"></span>
                </h4>

                <div class="space-y-6 relative">
                    <div class="bg-neutral-50 rounded-3xl p-6">
                        <div class="flex flex-col gap-1 border-b border-neutral-200 pb-5 mb-5">
                            <span class="text-[10px] font-black text-neutral-400 uppercase tracking-widest">Model
                                Perangkat</span>
                            <span
                                class="text-2xl font-bold text-neutral-800">{{ $old_phone_model ?: 'Belum diisi' }}</span>
                        </div>

                        <div class="grid grid-cols-2 gap-6">
                            <div class="flex flex-col gap-1">
                                <span
                                    class="text-[10px] font-black text-neutral-400 uppercase tracking-widest">Brand</span>
                                <span class="font-bold text-violet-600">{{ $old_phone_brand ?: '-' }}</span>
                            </div>
                            <div class="flex flex-col gap-1">
                                <span
                                    class="text-[10px] font-black text-neutral-400 uppercase tracking-widest">Storage</span>
                                <span class="font-bold text-neutral-800">{{ $old_phone_storage ?: '-' }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- Help Box moved inside summary --}}
                    <div class="bg-emerald-50 rounded-3xl p-5 border border-emerald-100 flex items-center gap-4">
                        <div
                            class="w-10 h-10 bg-emerald-100 text-emerald-600 rounded-xl flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </div>
                        <div class="text-xs">
                            <p class="font-black text-emerald-900">Kami Berikan Harga Terbaik!</p>
                            <p class="text-emerald-700 font-medium">Estimasi akan diberikan berdasarkan kondisi aktual
                                HP.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex flex-col-reverse md:flex-row justify-between items-center gap-4 pt-2">
                <button type="button" @click="step = 2"
                    class="w-full md:w-auto text-neutral-500 hover:text-neutral-800 font-bold px-6 py-4 transition-colors flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Edit Data
                </button>
                <div class="w-full md:w-auto">
                    <button type="button" wire:click="submit"
                        class="w-full md:w-auto bg-violet-600 hover:bg-violet-700 text-white px-10 py-4 rounded-2xl font-black text-lg transition-all active:scale-[0.97] shadow-xl shadow-violet-900/20">
                        Kirim Penawaran Sekarang
                    </button>
                    <p class="text-center text-[10px] text-neutral-400 mt-3 italic font-medium">
                        Dengan mengirim, Anda setuju dengan proses pengecekan teknis.
                    </p>
                </div>
            </div>
        </div>

    </div>
</div>
