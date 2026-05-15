<div>
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Daftar Perangkat Buyback</h1>
            <p class="text-sm text-gray-500 mt-1">Kelola data harga dasar HP untuk fitur Tukar Tambah & Jual HP.</p>
        </div>
        <div class="flex items-center gap-3">
            <button wire:click="syncTierDevice" wire:loading.attr="disabled"
                wire:confirm="Anda yakin ingin menyesuaikan tier semua perangkat dengan aturan harga saat ini?"
                class="bg-white border border-gray-200 text-gray-700 px-5 py-2.5 rounded-lg font-bold hover:bg-gray-50 hover:text-#1c69d4 transition shadow-sm flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                </svg>
                Sync Tier
                <svg wire:loading wire:target="syncTierDevice" class="animate-spin -ml-1 mr-2 h-4 w-4 text-#1c69d4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
            </button>
            <a href="{{ route('admin.buyback.create') }}" wire:navigate
                class="bg-[#1c69d4] text-white px-5 py-2.5 rounded-lg font-bold hover:bg-[#3f36b8] transition shadow-sm shadow-[#1c69d4]/30 flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Tambah Perangkat
            </a>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100 text-xs text-gray-400 font-bold uppercase tracking-wider">
                        <th class="p-4">Merek & Model</th>
                        <th class="p-4">Kapasitas</th>
                        <th class="p-4">Harga Dasar (Mulus 100%)</th>
                        <th class="p-4">Kategori Tier</th>
                        <th class="p-4 text-center">Status</th>
                        <th class="p-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($devices as $device)
                        <tr class="hover:bg-gray-50/50 transition">
                            <td class="p-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center font-bold text-gray-400">
                                        {{ substr($device->brand->name ?? '?', 0, 1) }}
                                    </div>
                                    <div>
                                        <p class="font-bold text-gray-900">{{ $device->model_name }}</p>
                                        <p class="text-xs text-gray-500 font-semibold">{{ $device->brand->name ?? 'Unknown' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="p-4">
                                <span class="px-3 py-1 bg-gray-100 text-gray-600 rounded-lg text-xs font-bold">
                                    {{ $device->ram ? $device->ram . ' / ' : '' }}{{ $device->storage }}
                                </span>
                            </td>
                            <td class="p-4">
                                <p class="font-bold text-gray-800">Rp {{ number_format($device->base_price, 0, ',', '.') }}</p>
                            </td>
                            <td class="p-4">
                                @if($device->tier)
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-[#1c69d4]/10 text-[#1c69d4] rounded-lg text-xs font-bold">
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                                        {{ $device->tier->name }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-amber-50 text-amber-600 rounded-lg text-xs font-bold">
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                                        Tidak Ada Tier
                                    </span>
                                @endif
                            </td>
                            <td class="p-4 text-center">
                                @if ($device->is_active)
                                    <span class="px-3 py-1 bg-emerald-50 text-emerald-600 text-[10px] uppercase tracking-wider font-black rounded-full">Aktif</span>
                                @else
                                    <span class="px-3 py-1 bg-gray-100 text-gray-500 text-[10px] uppercase tracking-wider font-black rounded-full">Nonaktif</span>
                                @endif
                            </td>
                            <td class="p-4 text-right">
                                <button class="p-2 text-#1c69d4 hover:bg-[#eff6ff] rounded-lg transition" title="Edit">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-8 text-center text-gray-500">
                                <div class="flex flex-col items-center justify-center">
                                    <svg class="w-12 h-12 text-gray-300 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" /></svg>
                                    <p class="font-medium text-gray-900">Belum ada perangkat yang dikonfigurasi</p>
                                    <p class="text-sm mt-1">Tambahkan perangkat pertama Anda untuk memulai fitur Buyback.</p>
                                    <a href="{{ route('admin.buyback.create') }}" wire:navigate class="mt-4 px-4 py-2 bg-white border border-gray-200 text-gray-700 font-bold rounded-lg hover:bg-gray-50 transition text-sm">
                                        Tambah Perangkat
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
