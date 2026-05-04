<div class="bg-gray-50 min-h-screen pb-20 pt-8">
    <div class="max-w-4xl mx-auto px-6">
        <div class="mb-8">
            <h1 class="text-3xl font-extrabold text-gray-900">Jual HP Saya</h1>
            <p class="text-gray-500 mt-1">Pantau status penawaran dan pengajuan jual HP Anda.</p>
        </div>

        <div class="space-y-4">
            @forelse($sells as $item)
                <a href="{{ route('sell-phone.show', $item) }}" wire:navigate
                    class="block bg-white rounded-2xl p-5 shadow-sm border border-gray-100 hover:shadow-md hover:border-gray-200 transition">
                    <div class="flex flex-col md:flex-row justify-between md:items-center gap-4">
                        <div class="flex items-center gap-4">
                            <div
                                class="w-16 h-16 bg-gray-50 rounded-xl flex items-center justify-center p-2 border border-gray-100 shrink-0">
                                <img src="{{ $item->getFirstMediaUrl('photos', 'thumb') }}"
                                    class="object-contain max-h-full max-w-full">
                            </div>
                            <div>
                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-0.5">Barang
                                    Dijual</p>
                                <h3 class="font-bold text-gray-900">{{ $item->phone_brand }} {{ $item->phone_model }}
                                </h3>
                            </div>
                        </div>

                        <div class="flex flex-col md:items-end justify-center mt-2 md:mt-0">
                            @php
                                $statusColors = [
                                    'PENDING' => 'bg-amber-100 text-amber-800',
                                    'OFFERED' => 'bg-blue-100 text-blue-800',
                                    'WAITING_FOR_DEVICE' => 'bg-purple-100 text-purple-800',
                                    'INSPECTING' => 'bg-indigo-100 text-indigo-800',
                                    'PAYING' => 'bg-teal-100 text-teal-800',
                                    'COMPLETED' => 'bg-emerald-100 text-emerald-800',
                                    'CANCELLED' => 'bg-rose-100 text-rose-800',
                                ];
                                $statusLabels = [
                                    'PENDING' => 'Menunggu Taksiran Admin',
                                    'OFFERED' => 'Penawaran Tersedia',
                                    'WAITING_FOR_DEVICE' => 'Menunggu HP Lama Anda via Kurir',
                                    'INSPECTING' => 'Inspeksi Fisik Oleh Admin',
                                    'PAYING' => 'Menunggu Pembayaran Akhir',
                                    'COMPLETED' => 'Selesai',
                                    'CANCELLED' => 'Dibatalkan',
                                ];
                            @endphp
                            <span
                                class="px-3 py-1 md:py-1.5 text-[11px] font-bold rounded-lg {{ $statusColors[$item->status] ?? 'bg-gray-100 text-gray-800' }}">
                                {{ $statusLabels[$item->status] ?? $item->status }}
                            </span>
                            @if ($item->appraised_value)
                                <p class="text-sm font-bold text-emerald-600 mt-2">Nilai Taksiran: Rp
                                    {{ number_format($item->appraised_value, 0, ',', '.') }}</p>
                            @else
                                <p class="text-xs text-gray-400 mt-2 italic">Belum ada taksiran harga</p>
                            @endif
                        </div>
                    </div>
                </a>
            @empty
                <div class="bg-white rounded-2xl p-10 shadow-sm border border-gray-100 text-center">
                    <svg class="w-16 h-16 text-gray-200 mx-auto mb-4" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                    </svg>
                    <h3 class="font-bold text-gray-900 text-lg">Belum ada pengajuan</h3>
                    <p class="text-gray-500 mt-1">Anda belum mengajukan tukar tambah apapun.</p>
                    <a href="{{ route('products.index') }}" wire:navigate
                        class="inline-block mt-4 bg-[#4E44DB] text-white px-6 py-2.5 rounded-xl font-bold hover:bg-[#3f36b8] transition">Mulai
                        Ajukan</a>
                </div>
            @endforelse
        </div>
    </div>
</div>
