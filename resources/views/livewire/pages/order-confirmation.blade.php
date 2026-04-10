<div class="bg-gray-50 min-h-screen pb-20" wire:poll.3s="checkStatus">
    <div class="max-w-2xl mx-auto px-6 pt-12">

        {{-- Verification / Success Icon --}}
        <div class="text-center mb-8">
            @if ($order->order_status === 'PENDING')
                <div class="w-20 h-20 bg-amber-100 rounded-full mx-auto flex items-center justify-center mb-4">
                    <svg class="w-10 h-10 text-amber-500 animate-spin" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                    </svg>
                </div>
                <h1 class="text-3xl font-extrabold text-gray-900">Pesanan Diterima!</h1>
                <p class="text-amber-600 mt-2 font-medium animate-pulse">Menunggu verifikasi pembayaran Anda...</p>
                <p class="text-gray-500 mt-1 text-sm">Nomor pesanan: <span class="font-bold text-[#4E44DB]">{{ $order->order_number }}</span></p>
            @else
                <div class="w-20 h-20 bg-emerald-100 rounded-full mx-auto flex items-center justify-center mb-4 animate-[bounce_1s_ease-in-out_2]">
                    <svg class="w-10 h-10 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <h1 class="text-3xl font-extrabold text-gray-900">Pembayaran Berhasil!</h1>
                <p class="text-emerald-600 mt-2 font-medium">Dana telah kami terima. Pesanan sedang diproses.</p>
                <p class="text-gray-500 mt-1 text-sm">Nomor pesanan: <span class="font-bold text-[#4E44DB]">{{ $order->order_number }}</span></p>
            @endif
        </div>

        {{-- Order Details Card --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-100 bg-gray-50/50">
                <div class="flex items-center justify-between">
                    <h2 class="font-bold text-gray-800">Detail Pesanan</h2>
                    <span
                        class="text-xs font-bold px-3 py-1 rounded-lg bg-amber-50 text-amber-600 border border-amber-100">
                        {{ $order->order_status }}
                    </span>
                </div>
                <p class="text-xs text-gray-400 mt-1">{{ $order->created_at->format('d M Y, H:i') }}</p>
            </div>

            {{-- Items --}}
            <div class="px-6 py-4 divide-y divide-gray-50">
                @foreach ($order->items as $item)
                    @php
                        $variant = $item->variant;
                        $product = $variant?->product;
                    @endphp
                    <div class="flex gap-4 py-3 first:pt-0 last:pb-0">
                        <div
                            class="w-14 h-14 rounded-xl bg-gray-50 overflow-hidden border border-gray-100 shrink-0 flex items-center justify-center">
                            @if ($product && $product->getFirstMediaUrl('cover', 'thumb'))
                                <img src="{{ $product->getFirstMediaUrl('cover', 'thumb') }}"
                                    alt="{{ $product->name }}" class="w-full h-full object-cover">
                            @else
                                <svg class="w-6 h-6 text-gray-300" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                </svg>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <h3 class="font-bold text-gray-800 text-sm truncate">
                                {{ $product?->name ?? 'Produk tidak tersedia' }}</h3>
                            <p class="text-xs text-gray-400">
                                {{ $variant?->ram ? $variant->ram . ' / ' : '' }}{{ $variant?->storage ?? '' }}
                                {{ $variant?->color ? '- ' . $variant->color : '' }}
                            </p>
                        </div>
                        <div class="text-right shrink-0">
                            <p class="text-xs text-gray-400">{{ $item->qty }}x</p>
                            <p class="font-bold text-gray-800 text-sm">
                                Rp {{ number_format($item->subtotal, 0, ',', '.') }}</p>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Totals --}}
            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/30 space-y-2">
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500">Subtotal</span>
                    <span class="font-semibold">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500">Ongkos Kirim</span>
                    <span class="font-semibold">Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between text-base pt-2 border-t border-gray-200">
                    <span class="font-bold text-gray-900">Grand Total</span>
                    <span class="font-black text-[#4E44DB] text-xl">Rp
                        {{ number_format($order->grand_total, 0, ',', '.') }}</span>
                </div>
            </div>

            {{-- Shipping Address --}}
            <div class="px-6 py-4 border-t border-gray-100">
                <h3 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Alamat Pengiriman</h3>
                <p class="font-bold text-gray-800 text-sm">
                    {{ $order->shipping_address_snapshot['recipient_name'] ?? '' }}</p>
                <p class="text-sm text-gray-500">
                    {{ $order->shipping_address_snapshot['phone_number'] ?? '' }}</p>
                <p class="text-sm text-gray-600 mt-1">
                    {{ $order->shipping_address_snapshot['full_address'] ?? '' }}</p>
            </div>
        </div>

        {{-- Actions --}}
        <div class="flex flex-col sm:flex-row gap-3 mt-6">
            @php
                $pendingPayment = $order->payments->where('status', 'PENDING')->last();
            @endphp
            
            @if ($order->order_status === 'PENDING' && $pendingPayment && $pendingPayment->xendit_invoice_url)
                <a href="{{ $pendingPayment->xendit_invoice_url }}" target="_blank"
                    class="flex-1 text-center bg-[#0097FF] text-white py-3.5 rounded-xl font-bold hover:bg-[#007ecc] transition shadow-lg shadow-[#0097FF]/25 flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                    </svg>
                    Bayar Tagihan
                </a>
            @endif

            <a href="{{ route('orders.index') }}" wire:navigate
                class="flex-1 text-center bg-white border-2 border-gray-200 text-gray-700 py-3.5 rounded-xl font-bold hover:bg-gray-50 transition">
                Pesanan Saya
            </a>
            @if ($order->order_status !== 'PENDING')
                <a href="{{ route('products.index') }}" wire:navigate
                    class="flex-1 text-center bg-[#4E44DB] text-white py-3.5 rounded-xl font-bold hover:bg-[#3f36b8] transition shadow-lg shadow-[#4E44DB]/25">
                    Lanjut Belanja
                </a>
            @endif
        </div>
    </div>
</div>
