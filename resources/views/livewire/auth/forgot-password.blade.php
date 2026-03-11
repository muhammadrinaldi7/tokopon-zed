<div class="flex items-center justify-center min-h-[calc(100vh-72px)] px-4 py-12 bg-gray-50">
    <div class="w-full max-w-md">
        {{-- Card --}}
        <div class="bg-white rounded-2xl shadow-lg shadow-gray-200/50 border border-gray-100 p-8">
            {{-- Logo --}}
            <div class="text-center mb-8">
                <a href="/" wire:navigate class="inline-block">
                    <span
                        class="text-3xl font-bold bg-linear-to-r from-[#0097FF] via-[#4E44DB] to-[#013559] bg-clip-text text-transparent">Tokopon</span>
                </a>
                <p class="mt-2 text-sm text-gray-500">Reset password Anda</p>
            </div>

            {{-- Success Message --}}
            @if ($linkSent)
                <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-xl">
                    <div class="flex items-center gap-2">
                        <svg class="shrink-0 text-green-500" xmlns="http://www.w3.org/2000/svg" width="20"
                            height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                            <polyline points="22 4 12 14.01 9 11.01"></polyline>
                        </svg>
                        <p class="text-sm text-green-700 font-medium">Link reset password telah dikirim ke email Anda.
                        </p>
                    </div>
                </div>
            @endif

            @if (session('status'))
                <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-xl">
                    <p class="text-sm text-green-700 font-medium">{{ session('status') }}</p>
                </div>
            @endif

            {{-- Info --}}
            <div class="mb-6 p-4 bg-blue-50 border border-blue-100 rounded-xl">
                <p class="text-sm text-blue-700">
                    Masukkan email Anda dan kami akan mengirimkan link untuk mereset password.
                </p>
            </div>

            {{-- Form --}}
            <form wire:submit="sendResetLink">
                {{-- Email --}}
                <div class="mb-6">
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1.5">Email</label>
                    <input wire:model="email" type="email" id="email" placeholder="nama@email.com"
                        class="w-full px-4 py-3 text-sm border border-gray-200 rounded-xl bg-gray-50 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 outline-none transition">
                    @error('email')
                        <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Submit --}}
                <button type="submit"
                    class="w-full py-3 text-sm font-semibold text-white bg-blue-500 rounded-xl shadow-md shadow-blue-500/30 transition hover:bg-blue-600 hover:shadow-lg hover:shadow-blue-500/40 disabled:opacity-50"
                    wire:loading.attr="disabled">
                    <span wire:loading.remove>Kirim Link Reset</span>
                    <span wire:loading>Mengirim...</span>
                </button>
            </form>
        </div>

        {{-- Back to Login --}}
        <p class="mt-6 text-center text-sm text-gray-500">
            Ingat password Anda?
            <a href="/login" wire:navigate class="font-semibold text-blue-500 hover:text-blue-600 transition">Kembali
                ke Login</a>
        </p>
    </div>
</div>
