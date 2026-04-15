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
                <p class="mt-2 text-sm text-gray-500">Buat akun baru</p>
            </div>

            {{-- Form --}}
            <form wire:submit="register">
                {{-- Name --}}
                <div class="mb-5">
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1.5">Nama Lengkap</label>
                    <input wire:model="name" type="text" id="name" placeholder="Nama lengkap Anda"
                        class="w-full px-4 py-3 text-sm border border-gray-200 rounded-xl bg-gray-50 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 outline-none transition">
                    @error('name')
                        <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Email --}}
                <div class="mb-5">
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1.5">Email</label>
                    <input wire:model="email" type="email" id="email" placeholder="nama@email.com"
                        class="w-full px-4 py-3 text-sm border border-gray-200 rounded-xl bg-gray-50 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 outline-none transition">
                    @error('email')
                        <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Password --}}
                <div class="mb-5">
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1.5">Password</label>
                    <input wire:model="password" type="password" id="password" placeholder="Minimal 8 karakter"
                        class="w-full px-4 py-3 text-sm border border-gray-200 rounded-xl bg-gray-50 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 outline-none transition">
                    @error('password')
                        <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Confirm Password --}}
                <div class="mb-6">
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1.5">Konfirmasi
                        Password</label>
                    <input wire:model="password_confirmation" type="password" id="password_confirmation"
                        placeholder="Ulangi password"
                        class="w-full px-4 py-3 text-sm border border-gray-200 rounded-xl bg-gray-50 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 outline-none transition">
                    @error('password_confirmation')
                        <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Submit --}}
                <button type="submit"
                    class="w-full py-3 text-sm font-semibold text-white bg-blue-500 rounded-xl shadow-md shadow-blue-500/30 transition hover:bg-blue-600 hover:shadow-lg hover:shadow-blue-500/40 disabled:opacity-50"
                    wire:loading.attr="disabled">
                    <span wire:loading.remove>Daftar</span>
                    <span wire:loading>Memproses...</span>
                </button>
            </form>

            {{-- Divider --}}
            <div class="relative my-6">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-gray-200"></div>
                </div>
                <div class="relative flex justify-center text-sm">
                    <span class="bg-white px-4 text-gray-400">atau</span>
                </div>
            </div>

            {{-- Google Register --}}
            <a href="{{ route('auth.google') }}"
                class="w-full flex items-center justify-center gap-3 py-3 text-sm font-medium text-gray-700 bg-white border border-gray-200 rounded-xl transition hover:bg-gray-50 hover:border-gray-300 hover:shadow-sm">
                <svg class="w-5 h-5" viewBox="0 0 24 24">
                    <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92a5.06 5.06 0 0 1-2.2 3.32v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.1z" fill="#4285F4"/>
                    <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                    <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
                    <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                </svg>
                Daftar dengan Google
            </a>
        </div>

        {{-- Login Link --}}
        <p class="mt-6 text-center text-sm text-gray-500">
            Sudah punya akun?
            <a href="/login" wire:navigate class="font-semibold text-blue-500 hover:text-blue-600 transition">Masuk</a>
        </p>
    </div>
</div>
