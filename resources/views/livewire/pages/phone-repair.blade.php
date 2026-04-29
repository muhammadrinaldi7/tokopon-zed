<section class="max-w-5xl mx-auto p-4 md:p-10">
    <div class="mb-10">
        <a href="/" class="flex items-center text-neutral-500 hover:text-orange-500 transition-colors mb-4">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            Kembali
        </a>
        <h1 class="text-3xl md:text-5xl font-black tracking-tighter">
            Repair <span class="text-orange-500">Service</span>
        </h1>
        <p class="text-neutral-500 mt-2">Beri tahu kami apa yang terjadi dengan perangkatmu.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2 space-y-8">

            <div class="bg-white rounded-3xl p-6 shadow-sm border border-neutral-100">
                <h3 class="text-lg font-bold mb-4 flex items-center">
                    <span
                        class="bg-orange-500 text-white w-7 h-7 rounded-full flex items-center justify-center text-sm mr-3">1</span>
                    Pilih Perangkat
                </h3>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                    @php
                        $brands = ['iPhone', 'Samsung', 'Oppo/Vivo', 'Lainnya'];
                    @endphp
                    @foreach ($brands as $brand)
                        <label class="cursor-pointer group">
                            <input type="radio" name="brand" class="peer hidden">
                            <div
                                class="p-4 border-2 border-neutral-100 rounded-2xl text-center transition-all peer-checked:border-orange-500 peer-checked:bg-orange-50 group-hover:border-orange-200">
                                <span
                                    class="text-sm font-semibold text-neutral-600 peer-checked:text-orange-600">{{ $brand }}</span>
                            </div>
                        </label>
                    @endforeach
                </div>
                <input type="text" placeholder="Masukkan seri model (contoh: iPhone 13 Pro)"
                    class="w-full mt-4 p-4 bg-neutral-50 border-none rounded-2xl focus:ring-2 focus:ring-orange-500 outline-none">
            </div>

            <div class="bg-white rounded-3xl p-6 shadow-sm border border-neutral-100">
                <h3 class="text-lg font-bold mb-4 flex items-center">
                    <span
                        class="bg-orange-500 text-white w-7 h-7 rounded-full flex items-center justify-center text-sm mr-3">2</span>
                    Apa Kerusakannya?
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    @php
                        $problems = [
                            ['icon' => '📱', 'label' => 'Layar Pecah / LCD'],
                            ['icon' => '🔋', 'label' => 'Baterai Boros / Drop'],
                            ['icon' => '📷', 'label' => 'Kamera Bermasalah'],
                            ['icon' => '💧', 'label' => 'Kena Air (Water Damage)'],
                            ['icon' => '⚡', 'label' => 'Tidak Bisa Nge-charge'],
                            ['icon' => '🛠️', 'label' => 'Lainnya / Mati Total'],
                        ];
                    @endphp
                    @foreach ($problems as $p)
                        <label class="cursor-pointer group">
                            <input type="checkbox" class="peer hidden">
                            <div
                                class="flex items-center p-4 border-2 border-neutral-100 rounded-2xl transition-all peer-checked:border-orange-500 peer-checked:bg-orange-50 group-hover:border-orange-200">
                                <span class="text-2xl mr-3">{{ $p['icon'] }}</span>
                                <span class="text-sm font-semibold text-neutral-600">{{ $p['label'] }}</span>
                            </div>
                        </label>
                    @endforeach
                </div>
            </div>

            <div class="bg-white rounded-3xl p-6 shadow-sm border border-neutral-100">
                <h3 class="text-lg font-bold mb-4 flex items-center">
                    <span
                        class="bg-orange-500 text-white w-7 h-7 rounded-full flex items-center justify-center text-sm mr-3">3</span>
                    Informasi Kontak
                </h3>
                <div class="space-y-4">
                    <input type="text" placeholder="Nama Lengkap"
                        class="w-full p-4 bg-neutral-50 border-none rounded-2xl focus:ring-2 focus:ring-orange-500 outline-none">
                    <input type="tel" placeholder="Nomor WhatsApp"
                        class="w-full p-4 bg-neutral-50 border-none rounded-2xl focus:ring-2 focus:ring-orange-500 outline-none">
                    <textarea placeholder="Catatan tambahan (opsional)" rows="3"
                        class="w-full p-4 bg-neutral-50 border-none rounded-2xl focus:ring-2 focus:ring-orange-500 outline-none"></textarea>
                </div>
            </div>
        </div>

        <div class="lg:col-span-1">
            <div class="bg-neutral-900 text-white rounded-3xl p-6 sticky top-10 shadow-xl overflow-hidden">
                <div class="absolute -top-10 -right-10 w-32 h-32 bg-orange-500/20 rounded-full blur-3xl"></div>

                <h3 class="text-xl font-bold mb-6 italic tracking-tight">Summary Repair</h3>

                <div class="space-y-4 border-b border-neutral-700 pb-6">
                    <div class="flex justify-between text-sm">
                        <span class="text-neutral-400">Perangkat</span>
                        <span class="font-bold">iPhone 13 Pro</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-neutral-400">Kerusakan</span>
                        <span class="font-bold">Layar & Baterai</span>
                    </div>
                </div>

                <div class="mt-6">
                    <p class="text-xs text-neutral-400 mb-1 leading-relaxed">
                        *Estimasi harga akan dikirimkan melalui WhatsApp setelah teknisi melakukan pengecekan awal.
                    </p>
                </div>

                <button
                    class="w-full mt-8 bg-orange-500 hover:bg-orange-600 text-white font-bold py-4 rounded-2xl transition-all transform hover:scale-[1.02] active:scale-95 shadow-lg shadow-orange-500/30">
                    Kirim Permintaan
                </button>
            </div>
        </div>
    </div>
</section>
