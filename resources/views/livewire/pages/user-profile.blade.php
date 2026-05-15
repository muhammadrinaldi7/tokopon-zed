<div class="min-h-dvh container pb-20 pt-8">
    <div class="max-w-6xl mx-auto px-4 md:px-6">
        <div class="mb-8">
            <h1 class="text-3xl font-extrabold text-gray-900">Profil Pengguna</h1>
            <p class="text-gray-500 mt-1">Lengkapi informasi Anda untuk kemudahan berbelanja dan berjualan.</p>
        </div>

        {{-- Progress Bars --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">
            <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-4">
                <div
                    class="w-12 h-12 rounded-full flex items-center justify-center font-bold {{ $buyerProgress >= 100 ? 'bg-emerald-100 text-emerald-600' : 'bg-amber-100 text-amber-600' }}">
                    @if ($buyerProgress >= 100)
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M5 13l4 4L19 7" />
                        </svg>
                    @else
                        {{ round($buyerProgress) }}%
                    @endif
                </div>
                <div class="flex-1">
                    <h3 class="text-sm font-bold text-gray-900">Status Pembeli</h3>
                    <div class="w-full bg-gray-100 rounded-full h-2 mt-2">
                        <div class="bg-emerald-500 h-2 rounded-full transition-all duration-500"
                            style="width: {{ $buyerProgress }}%"></div>
                    </div>
                </div>
            </div>

            <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-4">
                <div
                    class="w-12 h-12 rounded-full flex items-center justify-center font-bold {{ $sellerProgress >= 100 ? 'bg-[#4E44DB]/20 text-[#4E44DB]' : 'bg-rose-100 text-rose-600' }}">
                    @if ($sellerProgress >= 100)
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M5 13l4 4L19 7" />
                        </svg>
                    @else
                        {{ round($sellerProgress) }}%
                    @endif
                </div>
                <div class="flex-1">
                    <h3 class="text-sm font-bold text-gray-900">Status Penjual / Trade-in</h3>
                    <div class="w-full bg-gray-100 rounded-full h-2 mt-2">
                        <div class="bg-[#4E44DB] h-2 rounded-full transition-all duration-500"
                            style="width: {{ $sellerProgress }}%"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex flex-col md:flex-row gap-6">
            {{-- Sidebar Menu --}}
            <div class="w-full md:w-1/4">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden sticky top-6">
                    <div class="p-5 border-b border-gray-100 flex items-center gap-4">
                        <img src="{{ Auth::user()->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) }}"
                            class="w-12 h-12 rounded-full border border-gray-200">
                        <div>
                            <h3 class="font-bold text-gray-900 truncate">{{ Auth::user()->name }}</h3>
                            <p class="text-xs text-gray-500 truncate">{{ Auth::user()->email }}</p>
                        </div>
                    </div>
                    <div class="p-2 space-y-1">
                        <button wire:click="changeTab('profile')"
                            class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-bold transition-all {{ $activeTab === 'profile' ? 'bg-[#4E44DB]/10 text-[#4E44DB]' : 'text-gray-600 hover:bg-gray-50' }}">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            Data Pribadi
                        </button>
                        <button wire:click="changeTab('identity')"
                            class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-bold transition-all {{ $activeTab === 'identity' ? 'bg-[#4E44DB]/10 text-[#4E44DB]' : 'text-gray-600 hover:bg-gray-50' }}">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2" />
                            </svg>
                            Identitas (KTP & NPWP)
                        </button>
                        <button wire:click="changeTab('address')"
                            class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-bold transition-all {{ $activeTab === 'address' ? 'bg-[#4E44DB]/10 text-[#4E44DB]' : 'text-gray-600 hover:bg-gray-50' }}">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.242-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            Daftar Alamat
                        </button>
                        <button wire:click="changeTab('bank')"
                            class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-bold transition-all {{ $activeTab === 'bank' ? 'bg-[#4E44DB]/10 text-[#4E44DB]' : 'text-gray-600 hover:bg-gray-50' }}">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                            </svg>
                            Rekening Bank
                        </button>
                    </div>
                </div>
            </div>

            {{-- Main Content --}}
            <div class="w-full md:w-3/4">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 md:p-8">

                    {{-- TAB 1: DATA PRIBADI --}}
                    @if ($activeTab === 'profile')
                        <h2 class="text-xl font-bold text-gray-900 mb-6 border-b border-gray-100 pb-4">Data Pribadi</h2>
                        <form wire:submit.prevent="saveProfile" class="space-y-5">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-2">Nama Lengkap</label>
                                    <input type="text" wire:model="full_name"
                                        class="w-full p-2 rounded-xl border-gray-200 focus:border-[#4E44DB] focus:ring-[#4E44DB]/20"
                                        placeholder="Sesuai KTP">
                                    @error('full_name')
                                        <span class="text-xs text-rose-500 mt-1">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-2">No. Handphone
                                        (WhatsApp)</label>
                                    <input type="text" wire:model="phone_number"
                                        class="w-full rounded-xl p-2 border-gray-200 focus:border-[#4E44DB] focus:ring-[#4E44DB]/20"
                                        placeholder="0812xxxxxx">
                                    @error('phone_number')
                                        <span class="text-xs text-rose-500 mt-1">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-2">Tanggal Lahir</label>
                                    <input type="date" wire:model="birth_date"
                                        class="w-full rounded-xl p-2 border-gray-200 focus:border-[#4E44DB] focus:ring-[#4E44DB]/20">
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-2">Jenis Kelamin</label>
                                    <select wire:model="gender"
                                        class="w-full rounded-xl p-2 border-gray-200 focus:border-[#4E44DB] focus:ring-[#4E44DB]/20">
                                        <option value="">Pilih Jenis Kelamin</option>
                                        <option value="L">Laki-laki</option>
                                        <option value="P">Perempuan</option>
                                    </select>
                                </div>
                            </div>
                            <div class="flex justify-end pt-4">
                                <button type="submit"
                                    class="bg-gray-900 text-white px-6 py-2.5 rounded-xl font-bold hover:bg-[#4E44DB] transition shadow-md">Simpan
                                    Perubahan</button>
                            </div>
                        </form>
                    @endif

                    {{-- TAB 2: IDENTITAS --}}
                    @if ($activeTab === 'identity')
                        <div class="mb-6 border-b border-gray-100 pb-4">
                            <h2 class="text-xl font-bold text-gray-900">Verifikasi Identitas</h2>
                            <p class="text-sm text-gray-500 mt-1">Sesuai peraturan pemerintah, Anda diwajibkan
                                mengunggah identitas untuk transaksi Tukar Tambah / Jual HP.</p>
                        </div>
                        <form wire:submit.prevent="saveIdentity" class="space-y-6">
                            <div>
                                <label class="flex items-center gap-2 text-sm font-bold text-gray-700 mb-2">
                                    Nomor Induk Kependudukan (NIK)
                                    <span
                                        class="px-2 py-0.5 bg-rose-100 text-rose-600 text-[10px] uppercase rounded-full tracking-wider">Wajib</span>
                                </label>
                                <input type="text" wire:model="identity"
                                    class="w-full rounded-xl p-2 border-gray-200 focus:border-[#4E44DB] focus:ring-[#4E44DB]/20"
                                    placeholder="16 digit NIK">
                                @error('identity')
                                    <span class="text-xs text-rose-500 mt-1">{{ $message }}</span>
                                @enderror
                            </div>

                            <div>
                                <label class="flex items-center gap-2 text-sm font-bold text-gray-700 mb-2">
                                    Nomor Pokok Wajib Pajak (NPWP)
                                    <span
                                        class="px-2 py-0.5 bg-indigo-100 text-indigo-600 text-[10px] uppercase rounded-full tracking-wider">Jual
                                        HP</span>
                                </label>
                                <input type="text" wire:model="npwp"
                                    class="w-full rounded-xl p-2 border-gray-200 focus:border-[#4E44DB] focus:ring-[#4E44DB]/20"
                                    placeholder="Opsional untuk pembeli">
                            </div>

                            <div>
                                <label class="flex items-center gap-2 text-sm font-bold text-gray-700 mb-2">
                                    Foto KTP Bagian Depan
                                    <span
                                        class="px-2 py-0.5 bg-indigo-100 text-indigo-600 text-[10px] uppercase rounded-full tracking-wider">Jual
                                        HP</span>
                                </label>
                                @if ($current_ktp_photo_url)
                                    <div class="mb-4">
                                        <img src="{{ $current_ktp_photo_url }}"
                                            class="h-40 rounded-xl border border-gray-200 shadow-sm object-cover">
                                        <p class="text-xs text-emerald-600 font-bold mt-2">✓ KTP sudah diunggah</p>
                                    </div>
                                @endif
                                <div class="relative">
                                    <input type="file" wire:model="ktp_photo" accept="image/*"
                                        class="w-full text-sm  text-gray-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-bold file:bg-[#4E44DB]/10 file:text-[#4E44DB] hover:file:bg-[#4E44DB]/20 transition border border-gray-200 rounded-xl bg-gray-50 p-2 cursor-pointer">
                                </div>
                                <p class="text-xs text-gray-400 mt-2">Maksimal 5MB, format gambar (JPG/PNG). Tulisan
                                    KTP harus terbaca jelas.</p>
                                @error('ktp_photo')
                                    <span class="text-xs text-rose-500 mt-1">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="flex justify-end pt-4">
                                <button type="submit"
                                    class="bg-gray-900 text-white px-6 py-2.5 rounded-xl font-bold hover:bg-[#4E44DB] transition shadow-md">Simpan
                                    Identitas</button>
                            </div>
                        </form>
                    @endif

                    {{-- TAB 3: ALAMAT --}}
                    @if ($activeTab === 'address')
                        <div class="mb-6 border-b border-gray-100 pb-4">
                            <h2 class="text-xl font-bold text-gray-900">Buku Alamat</h2>
                            <p class="text-sm text-gray-500 mt-1">Alamat ini akan digunakan untuk pengiriman barang
                                jika Anda berbelanja.</p>
                        </div>
                        <form wire:submit.prevent="saveAddress" class="space-y-5">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-2">Nama Penerima</label>
                                    <input type="text" wire:model="recipient_name"
                                        class="w-full rounded-xl p-2 border-gray-200 focus:border-[#4E44DB] focus:ring-[#4E44DB]/20"
                                        placeholder="Nama lengkap">
                                    @error('recipient_name')
                                        <span class="text-xs text-rose-500 mt-1">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-2">No. HP Penerima</label>
                                    <input type="text" wire:model="address_phone"
                                        class="w-full rounded-xl p-2 border-gray-200 focus:border-[#4E44DB] focus:ring-[#4E44DB]/20"
                                        placeholder="08xxxx">
                                    @error('address_phone')
                                        <span class="text-xs text-rose-500 mt-1">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-bold text-gray-700 mb-2">Alamat Lengkap</label>
                                    <textarea wire:model="full_address" rows="3"
                                        class="w-full p-2 rounded-xl border-gray-200 focus:border-[#4E44DB] focus:ring-[#4E44DB]/20"
                                        placeholder="Nama jalan, Gedung, No. Rumah, RT/RW, Patokan..."></textarea>
                                    @error('full_address')
                                        <span class="text-xs text-rose-500 mt-1">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-2">Kode Pos</label>
                                    <input type="text" wire:model="postal_code"
                                        class="w-full rounded-xl p-2 border-gray-200 focus:border-[#4E44DB] focus:ring-[#4E44DB]/20"
                                        placeholder="Kode Pos">
                                    @error('postal_code')
                                        <span class="text-xs text-rose-500 mt-1">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="flex justify-end pt-4">
                                <button type="submit"
                                    class="bg-gray-900 text-white px-6 py-2.5 rounded-xl font-bold hover:bg-[#4E44DB] transition shadow-md">Simpan
                                    Alamat Utama</button>
                            </div>
                        </form>
                    @endif

                    {{-- TAB 4: REKENING BANK --}}
                    @if ($activeTab === 'bank')
                        <div class="mb-6 border-b border-gray-100 pb-4">
                            <h2 class="text-xl font-bold text-gray-900">Rekening Pembayaran</h2>
                            <p class="text-sm text-gray-500 mt-1">Kami akan mentransfer dana hasil Jual HP / Trade-In
                                ke rekening di bawah ini.</p>
                        </div>
                        <form wire:submit.prevent="saveBank" class="space-y-5">
                            <div>
                                <label class="flex items-center gap-2 text-sm font-bold text-gray-700 mb-2">
                                    Nama Bank / E-Wallet
                                    <span
                                        class="px-2 py-0.5 bg-indigo-100 text-indigo-600 text-[10px] uppercase rounded-full tracking-wider">Jual
                                        HP</span>
                                </label>
                                <input type="text" wire:model="bank_name"
                                    class="w-full rounded-xl p-2 border-gray-200 focus:border-[#4E44DB] focus:ring-[#4E44DB]/20"
                                    placeholder="Cth: BCA, Mandiri, GoPay, OVO">
                                @error('bank_name')
                                    <span class="text-xs text-rose-500 mt-1">{{ $message }}</span>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Nomor Rekening</label>
                                <input type="text" wire:model="account_number"
                                    class="w-full rounded-xl p-2 border-gray-200 focus:border-[#4E44DB] focus:ring-[#4E44DB]/20"
                                    placeholder="Ketik nomor rekening Anda">
                                @error('account_number')
                                    <span class="text-xs text-rose-500 mt-1">{{ $message }}</span>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Nama Pemilik Rekening</label>
                                <input type="text" wire:model="account_name"
                                    class="w-full rounded-xl p-2 border-gray-200 focus:border-[#4E44DB] focus:ring-[#4E44DB]/20"
                                    placeholder="Harus sesuai dengan nama di buku tabungan">
                                @error('account_name')
                                    <span class="text-xs text-rose-500 mt-1">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="flex justify-end pt-4">
                                <button type="submit"
                                    class="bg-gray-900 text-white px-6 py-2.5 rounded-xl font-bold hover:bg-[#4E44DB] transition shadow-md">Simpan
                                    Rekening Bank</button>
                            </div>
                        </form>
                    @endif

                </div>
            </div>
        </div>
    </div>
</div>
