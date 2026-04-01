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
        @foreach ($products as $product)
            <div class="shrink-0 w-40  md:w-50 lg:w-65">
                <div class="bg-neutral-200 rounded-2xl py-5">
                    <img src="{{ $product['image'] }}" alt="">
                </div>
                <div class="text-center py-2">
                    <h1 class="font-semibold text-xs md:text-sm lg:text-lg">{{ $product['name'] }} </h1>
                    <h1 class="text-neutral-400 text-[10px] md:text-xs lg:text-sm"> {{ $product['status'] }} </h1>
                    <h1 class="font-bold text-xs md:text-sm lg:text-lg mt-2">${{ $product['price'] }}</h1>
                </div>
                <button
                    class="text-center text-sm md:text-base py-1 md:py-2 font-bold border rounded-full w-full hover:bg-black hover:text-white transition-all duration-300">
                    Buy Now
                </button>
            </div>
        @endforeach
    </div>
    <div class="flex flex-col mt-8 gap-1 md:gap-2 justify-center items-center ">
        <h1 class=" font-bold text-lg md:text-3xl lg:text-5xl text-neutral-400">
            All <span
                class="bg-linear-to-r from-indigo-600 via-emerald-600 to-orange-500 bg-clip-text text-transparent">Products</span>
        </h1>
        <h1 class="font-semibold text-xs md:text-lg">All products are available</h1>
    </div>
    <div class="flex gap-2 md:gap-6 overflow-x-auto overflow-hidden no-scrollbar mt-4 md:mt-10 snap-x snap-mandatory">
        @foreach ($products as $product)
            <div class="shrink-0 w-40  md:w-50 lg:w-65">
                <div class="bg-neutral-200 rounded-2xl py-5">
                    <img src="{{ $product['image'] }}" alt="">
                </div>
                <div class="text-center py-2">
                    <h1 class="font-semibold text-xs md:text-sm lg:text-lg">{{ $product['name'] }} </h1>
                    <h1 class="text-neutral-400 text-[10px] md:text-xs lg:text-sm"> {{ $product['status'] }} </h1>
                    <h1 class="font-bold text-xs md:text-sm lg:text-lg mt-2">${{ $product['price'] }}</h1>
                </div>
                <button
                    class="text-center text-sm md:text-base py-1 md:py-2 font-bold border rounded-full w-full hover:bg-black hover:text-white transition-all duration-300">
                    Buy Now
                </button>
            </div>
        @endforeach
    </div>
</section>
