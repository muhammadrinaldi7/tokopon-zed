<section id="catalog" class="max-w-7xl mx-auto p-2 md:p-6">
    <div class="flex flex-col gap-1 md:gap-2 justify-center items-center ">
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
</section>
