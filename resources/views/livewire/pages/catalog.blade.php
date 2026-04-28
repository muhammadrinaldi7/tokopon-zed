<section id="catalog" class="max-w-7xl mx-auto p-2 md:p-6">
    <div class="flex flex-col gap-1 md:gap-2 justify-center items-center ">
        <h1 class=" font-bold text-lg md:text-3xl lg:text-5xl text-neutral-400">
            Now <span
                class="bg-linear-to-r from-indigo-600 via-emerald-600 to-orange-500 bg-clip-text text-transparent">Available</span>
        </h1>
        <h1 class="font-semibold text-xs md:text-lg">Best sellers and current promotions</h1>
    </div>
    <div class="flex gap-2 md:gap-6 overflow-x-auto overflow-hidden no-scrollbar mt-4 md:mt-10 snap-x snap-mandatory">
        @forelse ($products as $product)
            @php
                $imageUrl =
                    $product->getFirstMediaUrl('cover', 'thumb') ?:
                    $product->getFirstMediaUrl('gallery', 'thumb') ?:
                    $product->getFirstMediaUrl('cover') ?:
                    $product->getFirstMediaUrl('gallery');
                $variant = $product->variants->first();
                $status = $variant ? $variant->condition : 'New';
            @endphp
            <div class="shrink-0 w-40  md:w-50 lg:w-65">
                <div class="bg-neutral-200 rounded-2xl py-5 flex items-center justify-center p-2 aspect-square relative">
                    @if ($product->is_second)
                        <span
                            class="absolute top-2 left-2 z-10 bg-amber-500 text-white text-[9px] font-bold px-2 py-0.5 rounded-md shadow-sm">SECOND</span>
                    @endif
                    @if ($imageUrl)
                        <img src="{{ $imageUrl }}" class="w-full h-full object-contain" alt="">
                    @else
                        <svg class="w-16 h-16 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" />
                        </svg>
                    @endif
                </div>
                <div class="text-center py-2">
                    {{-- <h1 class="font-semibold text-xs md:text-sm lg:text-lg line-clamp-1">{{ $product->name }} </h1>
                    <h1 class="text-neutral-400 text-[10px] md:text-xs lg:text-sm"> {{ $status }} </h1> --}}
                    <h1 class="font-bold text-xs md:text-sm lg:text-lg mt-2">Rp
                        {{ number_format($product->starting_price ?? 0, 0, ',', '.') }}</h1>
                </div>
                <a href="{{ route('products.show', $product) }}" wire:navigate>
                    <button
                        class="text-center text-sm md:text-base py-1 md:py-2 font-bold border rounded-full w-full hover:bg-black hover:text-white transition-all duration-300">
                        Buy Now
                    </button>
                </a>
            </div>
        @empty
            <div class="col-span-full py-16 text-center w-full flex flex-col items-center justify-center opacity-30">
                <svg class="w-24 h-24 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                </svg>
                <p class="font-bold text-2xl">Produk Tidak Tersedia</p>
                <p class="text-sm">Maaf, saat ini belum ada produk pilihan untuk ditampilkan.</p>
            </div>
        @endforelse
    </div>
</section>
