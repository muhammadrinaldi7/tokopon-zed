<section id="buymobile" class="max-w-7xl mx-auto p-2 md:p-6">
    <div class="flex gap-2 ">
        <div class="bg-neutral-500 text-white px-3 flex justify-center items-center rounded-md">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-8 rotate-180">
                <path fill-rule="evenodd"
                    d="M4.5 5.653c0-1.427 1.529-2.33 2.779-1.643l11.54 6.347c1.295.712 1.295 2.573 0 3.286L7.28 19.99c-1.25.687-2.779-.217-2.779-1.643V5.653Z"
                    clip-rule="evenodd" />
            </svg>
        </div>
        <div class="w-full flex gap-4 items-center bg-blue-500 py-3 px-6 rounded-md">
            <img src="{{ asset('assets/png/buymobile.png') }}" class="w-10 h-auto" alt="">
            <h1 class="text-white text-4xl font-bold">Buy Mobile Phone</h1>
        </div>
    </div>
    <div class="flex gap-2 mt-4">
        <div
            class="bg-neutral-100 text-neutral-400 border border-neutral-200 px-3 flex justify-center items-center rounded-md">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-8">
                <path
                    d="M6 12a.75.75 0 0 1-.75-.75v-7.5a.75.75 0 1 1 1.5 0v7.5A.75.75 0 0 1 6 12ZM18 12a.75.75 0 0 1-.75-.75v-7.5a.75.75 0 0 1 1.5 0v7.5A.75.75 0 0 1 18 12ZM6.75 20.25v-1.5a.75.75 0 0 0-1.5 0v1.5a.75.75 0 0 0 1.5 0ZM18.75 18.75v1.5a.75.75 0 0 1-1.5 0v-1.5a.75.75 0 0 1 1.5 0ZM12.75 5.25v-1.5a.75.75 0 0 0-1.5 0v1.5a.75.75 0 0 0 1.5 0ZM12 21a.75.75 0 0 1-.75-.75v-7.5a.75.75 0 0 1 1.5 0v7.5A.75.75 0 0 1 12 21ZM3.75 15a2.25 2.25 0 1 0 4.5 0 2.25 2.25 0 0 0-4.5 0ZM12 11.25a2.25 2.25 0 1 1 0-4.5 2.25 2.25 0 0 1 0 4.5ZM15.75 15a2.25 2.25 0 1 0 4.5 0 2.25 2.25 0 0 0-4.5 0Z" />
            </svg>
        </div>
        <div class="flex overflow-x-auto gap-4 no-scrollbar">
            @foreach ($brands as $brand)
                <div
                    class="flex-none bg-white px-4 py-3 rounded-md border border-neutral-200 w-40 flex items-center justify-center">
                    <img src="{{ asset('assets/brand/' . $brand['image']) }}" class="w-30 h-auto object-contain"
                        alt="{{ $brand['name'] }}">
                </div>
            @endforeach
        </div>
    </div>
    <div class="flex flex-col mt-8 gap-1 md:gap-2 justify-center items-center ">
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
                <div
                    class="bg-neutral-200 rounded-2xl py-5 flex items-center justify-center p-2 aspect-square relative">
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
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                </svg>
                <p class="font-bold text-2xl">Produk Tidak Tersedia</p>
                <p class="text-sm">Maaf, saat ini belum ada produk pilihan untuk ditampilkan.</p>
            </div>
        @endforelse
    </div>
    <div class="flex flex-col mt-8 gap-1 md:gap-2 justify-center items-center ">
        <h1 class=" font-bold text-lg md:text-3xl lg:text-5xl text-neutral-400">
            All <span
                class="bg-linear-to-r from-indigo-600 via-emerald-600 to-orange-500 bg-clip-text text-transparent">Products</span>
        </h1>
        <h1 class="font-semibold text-xs md:text-lg">All products are available</h1>
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
                <div
                    class="bg-neutral-200 rounded-2xl py-5 flex items-center justify-center p-2 aspect-square relative">
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
                    <h1 class="font-semibold text-xs md:text-sm lg:text-lg line-clamp-1">{{ $product->name }} </h1>
                    {{-- <h1 class="text-neutral-400 text-[10px] md:text-xs lg:text-sm"> {{ $status }} </h1> --}}
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
                <svg class="w-20 h-20 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                </svg>
                <p class="font-bold text-xl">Katalog Kosong</p>
                <p class="text-sm">Belum ada produk yang tersedia saat ini.</p>
            </div>
        @endforelse
    </div>
</section>
