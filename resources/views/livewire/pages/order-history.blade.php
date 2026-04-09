<div class="bg-gray-50 min-h-screen pb-20">
    <div class="max-w-4xl mx-auto px-6 pt-8">
        <h1 class="text-3xl font-extrabold text-gray-900 mb-6">Pesanan Saya</h1>

        {{-- Status Filter --}}
        <div class="flex flex-wrap gap-2 mb-8">
            @foreach (['' => 'Semua', 'PENDING' => 'Menunggu', 'PROCESSING' => 'Diproses', 'SHIPPED' => 'Dikirim', 'COMPLETED' => 'Selesai', 'CANCELLED' => 'Dibatalkan'] as $value => $label)
                <button wire:click="$set('statusFilter', '{{ $value }}')"
                    @class([
                        'px-4 py-2 rounded-xl text-sm font-semibold transition-all',
                        'bg-[#4E44DB] text-white shadow-md shadow-[#4E44DB]/20' => $statusFilter === $value,
                        'bg-white text-gray-600 border border-gray-200 hover:border-[#4E44DB]/30' => $statusFilter !== $value,
                    ])>
                    {{ $label }}
                </button>
            @endforeach
        </div>

        {{-- Orders List --}}
        @forelse ($orders as $order)
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 mb-4 overflow-hidden">
                {{-- Order Header --}}
                <div class="px-6 py-4 border-b border-gray-50 flex items-center justify-between bg-gray-50/30">
                    <div>
                        <span class="font-bold text-gray-800 text-sm">{{ $order->order_number }}</span>
                        <span class="text-xs text-gray-400 ml-2">{{ $order->created_at->format('d M Y, H:i') }}</span>
                    </div>
                    @php
                        $statusColors = [
                            'PENDING' => 'bg-amber-50 text-amber-600 border-amber-100',
                            'PROCESSING' => 'bg-blue-50 text-blue-600 border-blue-100',
                            'SHIPPED' => 'bg-purple-50 text-purple-600 border-purple-100',
                            'COMPLETED' => 'bg-emerald-50 text-emerald-600 border-emerald-100',
                            'CANCELLED' => 'bg-rose-50 text-rose-600 border-rose-100',
                        ];
                        $statusLabels = [
                            'PENDING' => 'Menunggu Bayar',
                            'PROCESSING' => 'Diproses',
                            'SHIPPED' => 'Dikirim',
                            'COMPLETED' => 'Selesai',
                            'CANCELLED' => 'Dibatalkan',
                        ];
                    @endphp
                    <span class="text-xs font-bold px-3 py-1 rounded-lg border {{ $statusColors[$order->order_status] ?? 'bg-gray-50 text-gray-600' }}">
                        {{ $statusLabels[$order->order_status] ?? $order->order_status }}
                    </span>
                </div>

                {{-- Items Preview (max 2) --}}
                <div class="px-6 py-4">
                    @foreach ($order->items->take(2) as $item)
                        @php
                            $variant = $item->variant;
                            $product = $variant?->product;
                            $imgUrl = $product ? ($product->getFirstMediaUrl('cover', 'thumb') ?: $product->getFirstMediaUrl('gallery', 'thumb')) : '';
                        @endphp
                        <div class="flex gap-3 items-center {{ !$loop->last ? 'mb-3' : '' }}">
                            <div class="w-12 h-12 rounded-lg bg-gray-50 overflow-hidden border border-gray-100 shrink-0 flex items-center justify-center">
                                @if ($imgUrl)
                                    <img src="{{ $imgUrl }}" alt="" class="w-full h-full object-cover">
                                @else
                                    <svg class="w-5 h-5 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" /></svg>
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="font-semibold text-gray-800 text-sm truncate">{{ $product?->name ?? '-' }}</p>
                                <p class="text-xs text-gray-400">{{ $item->qty }}x · Rp {{ number_format($item->price_at_checkout, 0, ',', '.') }}</p>
                            </div>
                        </div>
                    @endforeach
                    @if ($order->items->count() > 2)
                        <p class="text-xs text-gray-400 mt-2">+{{ $order->items->count() - 2 }} produk lainnya</p>
                    @endif
                </div>

                {{-- Footer --}}
                <div class="px-6 py-4 border-t border-gray-50 flex items-center justify-between bg-gray-50/20">
                    <div>
                        <span class="text-xs text-gray-400">Total:</span>
                        <span class="font-black text-gray-900 ml-1">Rp {{ number_format($order->grand_total, 0, ',', '.') }}</span>
                    </div>
                    <a href="{{ route('orders.show', $order) }}" wire:navigate
                        class="text-sm font-bold text-[#4E44DB] hover:underline">
                        Lihat Detail →
                    </a>
                </div>
            </div>
        @empty
            <div class="bg-white rounded-2xl p-16 text-center shadow-sm border border-gray-100">
                <svg class="w-16 h-16 text-gray-200 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                </svg>
                <p class="text-gray-500 font-medium">Belum ada pesanan.</p>
                <a href="{{ route('products.index') }}" wire:navigate
                    class="inline-block mt-4 text-[#4E44DB] font-bold hover:underline">
                    Mulai Belanja →
                </a>
            </div>
        @endforelse

        @if ($orders->hasPages())
            <div class="mt-8">{{ $orders->links() }}</div>
        @endif
    </div>
</div>
