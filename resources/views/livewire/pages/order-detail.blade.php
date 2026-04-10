<div class="bg-gray-50 min-h-screen pb-20">
    <div class="max-w-3xl mx-auto px-6 pt-8">
        {{-- Header --}}
        <div class="mb-6">
            <a href="{{ route('orders.index') }}" wire:navigate
                class="text-sm font-medium text-gray-400 hover:text-[#4E44DB] transition">← Kembali ke Pesanan</a>
            <div class="flex items-center justify-between mt-2">
                <h1 class="text-2xl font-extrabold text-gray-900">{{ $order->order_number }}</h1>
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
                <span
                    class="text-sm font-bold px-4 py-1.5 rounded-xl border {{ $statusColors[$order->order_status] ?? '' }}">
                    {{ $statusLabels[$order->order_status] ?? $order->order_status }}
                </span>
            </div>
            <p class="text-sm text-gray-400 mt-1">Dipesan pada {{ $order->created_at->format('d M Y, H:i') }}</p>
        </div>

        {{-- Order Timeline --}}
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 mb-6">
            @php
                $steps = ['PENDING', 'PROCESSING', 'SHIPPED', 'COMPLETED'];
                $stepLabels = ['Pesanan Dibuat', 'Diproses', 'Dikirim', 'Selesai'];
                $currentIndex = array_search($order->order_status, $steps);
                if ($currentIndex === false) $currentIndex = -1;
            @endphp
            <div class="flex items-center justify-between">
                @foreach ($steps as $i => $step)
                    <div class="flex flex-col items-center flex-1">
                        <div @class([
                            'w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold transition-all',
                            'bg-[#4E44DB] text-white' => $i <= $currentIndex,
                            'bg-gray-100 text-gray-400' => $i > $currentIndex,
                        ])>
                            @if ($i < $currentIndex)
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                    stroke-width="3">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                </svg>
                            @else
                                {{ $i + 1 }}
                            @endif
                        </div>
                        <span class="text-[10px] font-semibold mt-1.5 text-center {{ $i <= $currentIndex ? 'text-[#4E44DB]' : 'text-gray-400' }}">
                            {{ $stepLabels[$i] }}
                        </span>
                    </div>
                    @if (!$loop->last)
                        <div class="flex-1 h-0.5 mx-1 {{ $i < $currentIndex ? 'bg-[#4E44DB]' : 'bg-gray-100' }} rounded-full -mt-5"></div>
                    @endif
                @endforeach
            </div>
        </div>

        {{-- Items --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 mb-6 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100">
                <h2 class="font-bold text-gray-800">Produk Dipesan</h2>
            </div>
            <div class="divide-y divide-gray-50">
                @foreach ($order->items as $item)
                    @php
                        $variant = $item->variant;
                        $product = $variant?->product;
                        $imgUrl = $product ? ($product->getFirstMediaUrl('cover', 'thumb') ?: $product->getFirstMediaUrl('gallery', 'thumb')) : '';
                    @endphp
                    <div class="flex gap-4 px-6 py-4">
                        <div class="w-16 h-16 rounded-xl bg-gray-50 overflow-hidden border border-gray-100 shrink-0 flex items-center justify-center">
                            @if ($imgUrl)
                                <img src="{{ $imgUrl }}" alt="" class="w-full h-full object-cover">
                            @else
                                <svg class="w-6 h-6 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" /></svg>
                            @endif
                        </div>
                        <div class="flex-1">
                            <h3 class="font-bold text-gray-800 text-sm">{{ $product?->name ?? '-' }}</h3>
                            <p class="text-xs text-gray-400 mt-0.5">
                                {{ $variant?->ram ? $variant->ram . ' / ' : '' }}{{ $variant?->storage ?? '' }}
                                {{ $variant?->color ? '- ' . $variant->color : '' }}
                                · {{ $variant?->condition }}
                            </p>
                            <div class="flex items-center justify-between mt-2">
                                <span class="text-xs text-gray-500">{{ $item->qty }}x @ Rp {{ number_format($item->price_at_checkout, 0, ',', '.') }}</span>
                                <span class="font-bold text-gray-800">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Totals --}}
            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/30 space-y-2">
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500">Subtotal</span>
                    <span>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500">Ongkos Kirim</span>
                    <span>Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</span>
                </div>
                @if ($order->discount_amount > 0)
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Diskon</span>
                        <span class="text-emerald-600">-Rp {{ number_format($order->discount_amount, 0, ',', '.') }}</span>
                    </div>
                @endif
                <div class="flex justify-between pt-2 border-t border-gray-200">
                    <span class="font-bold text-gray-900">Grand Total</span>
                    <span class="font-black text-[#4E44DB] text-xl">Rp {{ number_format($order->grand_total, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        {{-- Shipping Address --}}
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 mb-6">
            <h2 class="font-bold text-gray-800 mb-3">Alamat Pengiriman</h2>
            <p class="font-semibold text-gray-800">{{ $order->shipping_address_snapshot['recipient_name'] ?? '' }}</p>
            <p class="text-sm text-gray-500">{{ $order->shipping_address_snapshot['phone_number'] ?? '' }}</p>
            <p class="text-sm text-gray-600 mt-1">{{ $order->shipping_address_snapshot['full_address'] ?? '' }}</p>
            @if (!empty($order->shipping_address_snapshot['postal_code']))
                <p class="text-sm text-gray-400 mt-1">Kode Pos: {{ $order->shipping_address_snapshot['postal_code'] }}</p>
            @endif
        </div>

        {{-- Shipping Info --}}
        @if ($order->shipping)
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 mb-6">
                <h2 class="font-bold text-gray-800 mb-3">Info Pengiriman</h2>
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <p class="text-gray-400">Kurir</p>
                        <p class="font-semibold text-gray-800">{{ strtoupper($order->shipping->courier_company ?? '-') }} ({{ $order->shipping->courier_type ?? '-' }})</p>
                    </div>
                    <div>
                        <p class="text-gray-400">No. Resi</p>
                        <p class="font-semibold text-gray-800 font-mono">{{ $order->shipping->tracking_number ?? 'Belum tersedia' }}</p>
                    </div>
                </div>
            </div>
        @endif

        {{-- Actions --}}
        <div class="flex flex-col sm:flex-row gap-3 mt-8">
            @if ($order->order_status === 'PENDING')
                @php
                    $pendingPayment = $order->payments->where('status', 'PENDING')->last();
                @endphp
                @if ($pendingPayment && $pendingPayment->xendit_invoice_url)
                    <a href="{{ $pendingPayment->xendit_invoice_url }}" target="_blank"
                        class="flex-1 bg-[#0097FF] text-white py-3.5 rounded-xl font-bold hover:bg-[#007ecc] transition shadow-lg shadow-[#0097FF]/25 text-center flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                        </svg>
                        Lanjutkan Pembayaran (Via Xendit)
                    </a>
                @else
                    <p class="text-sm text-amber-600 bg-amber-50 p-4 rounded-xl w-full text-center border border-amber-100">Menunggu *update* Link Pembayaran dari sistem.</p>
                @endif
            @endif

            @if ($order->order_status === 'SHIPPED')
                <button wire:click="confirmReceived"
                    class="flex-1 bg-emerald-500 text-white py-3.5 rounded-xl font-bold hover:bg-emerald-600 transition shadow-lg shadow-emerald-500/25 text-center"
                    wire:loading.attr="disabled">
                    <span wire:loading.remove wire:target="confirmReceived">Konfirmasi Diterima</span>
                    <span wire:loading wire:target="confirmReceived">Memproses...</span>
                </button>
            @endif

            @if ($order->order_status === 'COMPLETED')
                <a href="{{ route('products.show', $order->items->first()?->variant?->product) }}" wire:navigate
                    class="flex-1 bg-[#4E44DB] text-white py-3.5 rounded-xl font-bold hover:bg-[#3f36b8] transition shadow-lg shadow-[#4E44DB]/25 text-center">
                    Tulis Review
                </a>
            @endif
        </div>
    </div>
</div>
