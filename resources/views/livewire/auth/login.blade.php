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
                <p class="mt-2 text-sm text-gray-500">Masuk ke akun Anda</p>
            </div>

            {{-- Form --}}
            <form wire:submit="login">
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
                    <div class="flex items-center justify-between mb-1.5">
                        <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                        <a href="/forgot-password" wire:navigate
                            class="text-xs text-blue-500 hover:text-blue-600 transition">Lupa password?</a>
                    </div>
                    <input wire:model="password" type="password" id="password" placeholder="••••••••"
                        class="w-full px-4 py-3 text-sm border border-gray-200 rounded-xl bg-gray-50 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 outline-none transition">
                    @error('password')
                        <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Remember Me --}}
                <div class="flex items-center mb-6">
                    <input wire:model="remember" type="checkbox" id="remember"
                        class="w-4 h-4 text-blue-500 border-gray-300 rounded focus:ring-blue-500">
                    <label for="remember" class="ml-2 text-sm text-gray-600">Ingat saya</label>
                </div>

                {{-- Submit --}}
                <button type="submit"
                    class="w-full py-3 text-sm font-semibold text-white bg-blue-500 rounded-xl shadow-md shadow-blue-500/30 transition hover:bg-blue-600 hover:shadow-lg hover:shadow-blue-500/40 disabled:opacity-50"
                    wire:loading.attr="disabled">
                    <span wire:loading.remove>Masuk</span>
                    <span wire:loading>Memproses...</span>
                </button>
            </form>
        </div>

        {{-- Register Link --}}
        <p class="mt-6 text-center text-sm text-gray-500">
            Belum punya akun?
            <a href="/register" wire:navigate class="font-semibold text-blue-500 hover:text-blue-600 transition">Daftar
                sekarang</a>
        </p>
    </div>
</div>
