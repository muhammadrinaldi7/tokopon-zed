<div>
    <div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-extrabold text-gray-900">Pengaturan Pembayaran</h1>
            <p class="text-gray-500 text-sm mt-1">Konfigurasi Gateway Pembayaran (Xendit) untuk aplikasi Anda.</p>
        </div>
    </div>

    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden max-w-4xl">
        {{-- Header Section --}}
        <div class="px-8 py-6 border-b border-gray-100 bg-gray-50/50 flex items-start sm:items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-[#0097FF]/10 text-[#0097FF] flex items-center justify-center shrink-0">
                <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                </svg>
            </div>
            <div>
                <h2 class="font-bold text-gray-800 text-lg">Konfigurasi Xendit</h2>
                <p class="text-xs text-gray-500 mt-1">
                    API Key ini tersimpan secara terenkripsi di database demi keamanan. Anda bisa mendapatkan kunci ini dari dashboard Xendit.
                </p>
            </div>
        </div>

        {{-- Form Section --}}
        <form wire:submit="saveSettings" class="p-8 space-y-6">
            {{-- Secret Key --}}
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2 ml-1">Secret Key / API Key</label>
                <input type="password" wire:model="xenditSecretKey" placeholder="xnd_development_..."
                    class="w-full text-sm rounded-xl border-gray-200 px-4 py-3.5 focus:ring-2 focus:ring-[#0097FF]/20 focus:border-[#0097FF] transition">
                <p class="text-xs text-gray-400 mt-2 ml-1">Digunakan untuk membuat invoice (Sisi Server).</p>
                @error('xenditSecretKey') <span class="text-xs text-rose-500 ml-1 mt-1 block">{{ $message }}</span> @enderror
            </div>

            {{-- Public Key --}}
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2 ml-1">Public Key</label>
                <input type="password" wire:model="xenditPublicKey" placeholder="xnd_public_..."
                    class="w-full text-sm rounded-xl border-gray-200 px-4 py-3.5 focus:ring-2 focus:ring-[#0097FF]/20 focus:border-[#0097FF] transition">
                <p class="text-xs text-gray-400 mt-2 ml-1">Digunakan untuk tokenisasi kartu kredit (Opsional jika hanya pakai VA/E-Wallet/QRIS).</p>
                @error('xenditPublicKey') <span class="text-xs text-rose-500 ml-1 mt-1 block">{{ $message }}</span> @enderror
            </div>

            {{-- Webhook Token --}}
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2 ml-1">Callback Verification Token</label>
                <input type="password" wire:model="xenditWebhookToken" placeholder="Token untuk memvalidasi Callback HTTP..."
                    class="w-full text-sm rounded-xl border-gray-200 px-4 py-3.5 focus:ring-2 focus:ring-[#0097FF]/20 focus:border-[#0097FF] transition">
                <p class="text-xs text-gray-400 mt-2 ml-1">Cari token ini di Settings -> Callbacks. Digunakan untuk memastikan request dari Xendit asli.</p>
                @error('xenditWebhookToken') <span class="text-xs text-rose-500 ml-1 mt-1 block">{{ $message }}</span> @enderror
            </div>

            {{-- Payment Channels Checkboxes --}}
            <div class="pt-4">
                <label class="block text-sm font-bold text-gray-700 mb-3 ml-1">Metode Pembayaran Aktif</label>
                <p class="text-xs text-gray-500 mb-4 ml-1">Pilih metode pembayaran apa saja yang ingin Anda tampilkan saat pelanggan membuat Checkout (Invoice). Pastikan metode ini juga sudah di-aktifkan di Dashboard Xendit Anda.</p>
                
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3">
                    @foreach ($availableChannels as $code => $label)
                        <label class="flex items-start gap-3 p-3 border border-gray-200 rounded-xl cursor-pointer hover:bg-gray-50 transition-colors {{ in_array($code, $activePaymentChannels) ? 'bg-[#0097FF]/5 border-[#0097FF]' : '' }}">
                            <input type="checkbox" wire:model.live="activePaymentChannels" value="{{ $code }}"
                                class="mt-0.5 w-4 h-4 text-[#0097FF] border-gray-300 rounded focus:ring-[#0097FF]">
                            <span class="text-xs font-semibold text-gray-700">{{ $label }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            {{-- Actions --}}
            <div class="pt-6 border-t border-gray-100 flex justify-end">
                <button type="submit"
                    class="bg-[#4E44DB] text-white px-8 py-3.5 rounded-xl font-bold hover:bg-[#3f36b8] active:scale-[0.98] transition-all shadow-lg shadow-[#4E44DB]/25 flex items-center justify-center gap-2"
                    wire:loading.attr="disabled">
                    <span wire:loading.remove wire:target="saveSettings">Simpan Perubahan</span>
                    <span wire:loading wire:target="saveSettings" class="flex items-center gap-2">
                        <svg class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                        </svg>
                        Menyimpan...
                    </span>
                </button>
            </div>
        </form>
    </div>
</div>
