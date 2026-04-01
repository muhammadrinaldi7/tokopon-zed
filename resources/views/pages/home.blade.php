<?php
// ⚡ home

use Livewire\Attributes\Title;
use Livewire\Component;
use App\Models\Product;

new #[Title('Home - TokoPun')] class extends Component {
    public function with(): array
    {
        return [
            'featuredProducts' => Product::with(['category', 'brand', 'media'])
                ->where('is_active', true)
                ->orderByDesc('id')
                ->take(4)
                ->get(),
        ];
    }
};

?>

<div class="pb-20 bg-white">
    <livewire:pages.service-selection />

    {{-- Value Proposition Section --}}
    <div class="max-w-7xl mx-auto px-6 mt-12 mb-20 grid grid-cols-1 md:grid-cols-2 gap-10 items-center">
        <div class="space-y-4">
            <div class="flex items-center gap-4 bg-white border border-gray-100 shadow-xs p-4 rounded-xl">
                <div class="bg-[#4E44DB] text-white p-2 rounded-full">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <h3 class="font-bold text-gray-800 text-lg">Transparent pricing</h3>
            </div>
            <div class="flex items-center gap-4 bg-white border border-gray-100 shadow-xs p-4 rounded-xl">
                <div class="bg-[#4E44DB] p-2 text-white rounded-full">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                    </svg>
                </div>
                <h3 class="font-bold text-gray-800 text-lg">Fast & secure process</h3>
            </div>
            <div class="flex items-center gap-4 bg-white border border-gray-100 shadow-xs p-4 rounded-xl">
                <div class="bg-[#4E44DB] text-white p-2 rounded-full">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                </div>
                <h3 class="font-bold text-gray-800 text-lg">Service warranty</h3>
            </div>
        </div>
        <div class="text-gray-500 leading-relaxed space-y-6">
            <p>
                <strong class="text-gray-900">Tokopon</strong> is a trusted mobile phone service company providing
                complete solutions for your smartphone needs.
            </p>
            <p>
                We understand that a mobile phone is more than just a device, it's a part of your daily life. That's why
                Tokopon is committed to delivering quality products, honest pricing, and reliable service, <span
                    class="text-rose-500 font-semibold">all in one place.</span>
            </p>
        </div>
    </div>

    {{-- Featured Products Section --}}
    <div class="max-w-7xl mx-auto px-6 mt-16 relative">
        <div class="text-center mb-10 relative">
            <h2 class="text-3xl md:text-5xl font-bold text-gray-400 mb-2">
                Now
                <span class="text-[#4E44DB]">A</span><span class="text-[#4E44DB]">v</span><span
                    class="text-[#4E44DB]">a</span><span class="text-[#4E44DB]">i</span><span
                    class="text-orange-500">l</span><span class="text-orange-500">a</span><span
                    class="text-orange-500">b</span><span class="text-[#4E44DB]">l</span><span
                    class="text-[#4E44DB]">e</span>
            </h2>
            <p class="text-gray-800 font-semibold">Best sellers and current promotions</p>

            <div class="absolute right-0 bottom-0 top-0 flex items-end md:items-center">
                <a href="{{ route('products.index') }}" wire:navigate
                    class="font-bold text-gray-800 hover:text-[#4E44DB] transition-colors text-sm">
                    View all...
                </a>
            </div>
        </div>

        @if ($featuredProducts->isEmpty())
            <div class="bg-gray-50 rounded-3xl p-12 text-center border border-gray-100">
                <p class="text-gray-500 font-medium">Belum ada produk yang tersedia.</p>
            </div>
        @else
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-6">
                @foreach ($featuredProducts as $product)
                    <div class="bg-white flex flex-col items-center group cursor-pointer transition-all w-full">
                        {{-- Product Image --}}
                        <div
                            class="relative w-full aspect-4/5 bg-gray-100 rounded-3xl flex items-center justify-center overflow-hidden mb-4 transition-transform group-hover:bg-gray-200">
                            @php
                                $imageUrl = $product->getFirstMediaUrl('cover', 'thumb')
                                    ?: $product->getFirstMediaUrl('gallery', 'thumb')
                                    ?: $product->getFirstMediaUrl('cover')
                                    ?: $product->getFirstMediaUrl('gallery');
                            @endphp
                            @if ($imageUrl)
                                <img src="{{ $imageUrl }}" alt="{{ $product->name }}"
                                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            @else
                                <svg class="w-20 h-20 text-gray-300 drop-shadow-md" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                </svg>
                            @endif
                        </div>

                        {{-- Product Info --}}
                        <div class="flex flex-col items-center text-center w-full px-2 space-y-1">
                            <h3
                                class="font-bold text-gray-900 text-sm md:text-base whitespace-nowrap overflow-hidden text-ellipsis w-full">
                                {{ $product->name }}
                            </h3>
                            <span class="text-[10px] text-gray-400 font-medium uppercase tracking-widest">
                                {{ $product->brand ? $product->brand->name : 'New' }}
                            </span>
                            <p class="font-black text-gray-900 text-lg mt-1 block">
                                Rp {{ number_format($product->starting_price ?? 0, 0, ',', '.') }}
                            </p>

                            <button
                                class="mt-3 w-full border-2 border-gray-300 text-gray-800 hover:border-[#4E44DB] hover:bg-[#4E44DB] hover:text-white transition-all font-bold text-xs py-2 rounded-full shadow-sm">
                                Buy Now
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
