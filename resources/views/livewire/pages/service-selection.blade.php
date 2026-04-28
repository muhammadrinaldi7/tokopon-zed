<section id="serviceselection" class="max-w-7xl mx-auto p-2 md:p-6">

    <h1 class="text-center font-bold md:text-3xl lg:text-5xl text-neutral-400 py-2 ">
        Pilih <span
            class="bg-linear-to-r from-indigo-600 via-emerald-600 to-orange-500 bg-clip-text text-transparent">Kebutuhanmu</span>
    </h1>

    <div class="mt-4 grid grid-cols-3 gap-2 md:gap-4 lg:gap-6 ">
        <div class="group relative rounded-lg md:rounded-2xl bg-blue-500 p-2 md:p-6 lg:p-8 text-white shadow-xl aspect-2/3 flex flex-col justify-between overflow-hidden cursor-pointer"
            wire:click="navigateToBuyMobile">
            <div class="relative w-full h-1/2 flex justify-end mt-6">

                <img src="{{ asset('assets/png/buymobile3.png') }}" alt="gold phone"
                    class="absolute w-21  md:w-40 lg:w-60 h-auto -translate-y-5 md:-translate-y-8 -translate-x-1 md:-translate-x-5 transition-all duration-500 ease-in-out group-hover:-translate-x-3 md:group-hover:-translate-x-15 md:group-hover:-translate-y-11 group-hover:-rotate-10 z-10">

                <img src="{{ asset('assets/png/buymobile2.png') }}" alt="purple phone"
                    class="absolute w-18 md:w-35 lg:w-55 h-auto -translate-y-3 md:-translate-y-5 translate-x-0 md:-translate-x-2 transition-all duration-500 ease-in-out group-hover:-rotate-6 group-hover:-translate-x-1  md:group-hover:-translate-x-6 group-hover:-translate-y-3 md:group-hover:-translate-y-7  z-20">

                <img src="{{ asset('assets/png/buymobile1.png') }}" alt="blue phone"
                    class="absolute w-15 md:w-30 lg:w-50 h-auto transition-all duration-500 ease-in-out z-30">
            </div>

            <div class="w-full flex justify-between items-end mt-6">
                <h2 class="text-sm md:text-3xl lg:text-4xl font-bold leading-none tracking-tight">
                    Buy<br>Mobile<br>Phones
                </h2>

                <a href=""
                    class="rounded-full bg-white text-[#0090FF] h-6 md:h-10 w-auto flex items-center justify-center  p-1.5 md:p-2.5 shadow-md group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-full h-full" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2.5">
                        <path d="M5 12h14m-7-7 7 7-7 7" />
                    </svg>
                </a>
            </div>
        </div>

        {{--  --}}
        <div class="flex flex-col gap-2 md:gap-4 lg:gap-6 ">
            <div wire:click="navigateToRepair"
                class="w-full h-full bg-orange-500 rounded-lg md:rounded-2xl relative flex overflow-hidden p-2 md:p-6 lg:p-8 group cursor-pointer">

                <div class="absolute w-1/2 right-0">
                    <img src="{{ asset('assets/png/repair1.png') }}" alt="phonerepair"
                        class="absolute w-[43px] md:w-23 lg:w-38 right-3 md:right-8 lg:right-10 -top-1 md:-top-5 lg:-top-6 h-auto transition-all duration-500 ease-in-out group-hover:scale-105 z-10">
                    <img src="{{ asset('assets/png/repair2.png') }}" alt="phonerepair"
                        class="absolute  w-[27px] md:w-15 lg:w-25 right-9 md:right-20 top-2 lg:right-30  h-auto transition-all duration-500 ease-in-out group-hover:scale-105 group-hover:-rotate-6 md:group-hover:-translate-x-5 lg:group-hover:-translate-x-5 z-10">
                </div>
                <div class="w-full flex justify-between items-end mt-6">
                    <h2 class="text-sm md:text-2xl lg:text-4xl text-white font-bold leading-none tracking-tight">
                        Phone <br> Repair
                    </h2>

                    <a href="{{ route('phone-repair') }}"
                        class="rounded-full bg-white text-[#0090FF] h-4 md:h-8 lg:h-10 w-auto flex items-center justify-center p-1 md:p-2 lg:p-2.5 shadow-md group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-full h-full" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2.5">
                            <path d="M5 12h14m-7-7 7 7-7 7" />
                        </svg>
                    </a>
                </div>

            </div>
            <div wire:click="navigateToTradeIn"
                class="w-full h-full bg-emerald-500 rounded-lg md:rounded-2xl relative flex overflow-hidden p-2 md:p-6 lg:p-8 group cursor-pointer">
                <div class="absolute w-1/2 right-0">
                    <img src="{{ asset('assets/png/trade1.png') }}" alt="phonerepair"
                        class="absolute w-[43px] md:w-23 lg:w-38 right-3 md:right-8 lg:right-10 -top-1 md:-top-5 lg:-top-6 h-auto transition-all duration-500 ease-in-out group-hover:scale-105 z-10">
                    <img src="{{ asset('assets/png/trade2.png') }}" alt="phonerepair"
                        class="absolute w-[24px] md:w-13 lg:w-20 right-10 md:right-23 top-1 md:top-0 lg:top-2 lg:right-35  h-auto transition-all duration-500 ease-in-out group-hover:scale-105 group-hover:rotate-180 z-10">
                </div>

                <div class="w-full flex justify-between items-end mt-6">
                    <h2 class="text-sm md:text-2xl lg:text-4xl text-white font-bold leading-none tracking-tight">
                        Trade- <br> In <br>Mobile <br> Phones
                    </h2>

                    <a href="#"
                        class="rounded-full bg-white text-[#0090FF] h-4 md:h-8 lg:h-10 w-auto flex items-center justify-center p-1 md:p-2 lg:p-2.5 shadow-md group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-full h-full" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2.5">
                            <path d="M5 12h14m-7-7 7 7-7 7" />
                        </svg>
                    </a>
                </div>
            </div>
        </div>
        <div class="flex flex-col gap-2 md:gap-4 lg:gap-6 ">
            <div
                class="w-full h-full bg-violet-600 rounded-lg md:rounded-2xl relative flex overflow-hidden p-2 md:p-6 lg:p-8 group cursor-pointer">
                <div class="absolute w-full right-0">
                    <img src="{{ asset('assets/png/sell1.png') }}" alt="phonerepair"
                        class="absolute -top-1 md:-top-5 lg:-top-6 right-3 md:right-5 w-[43px] md:w-22 lg:w-35 h-auto transition-all duration-500 ease-in-out group-hover:scale-105 z-10">
                    <img src="{{ asset('assets/png/sell2.png') }}" alt="phonerepair"
                        class="absolute top-2 md:top-4 right-10 md:right-19 lg:right-24 w-[23px] md:w-12 lg:w-25  h-auto transition-all duration-500 ease-in-out group-hover:scale-105 group-hover:-rotate-6 group-hover:-translate-x-2 md:group-hover:-translate-x-6 z-10">
                    <img src="{{ asset('assets/png/sell3.png') }}" alt="phonerepair"
                        class="absolute top-11 md:top-20 lg:top-33 right-10 md:right-19 lg:right-25 w-[10px] md:w-5 lg:w-8  h-auto transition-all duration-500 ease-in-out group-hover:scale-105  z-10 group-hover:animate-bounce">
                    <img src="{{ asset('assets/png/sell4.png') }}" alt="phonerepair"
                        class="absolute top-12 md:top-22 lg:top-36 right-12 md:right-23 lg:right-32 w-[8px] md:w-4 lg:w-7  h-auto transition-all duration-500 ease-in-out group-hover:scale-105  z-10 group-hover:animate-bounce">
                </div>

                <div class="w-full flex justify-between items-end mt-6">
                    <h2 class="text-sm md:text-2xl lg:text-4xl text-white font-bold leading-none tracking-tight">
                        Sell <br> Mobile <br> Phones
                    </h2>

                    <a href="#"
                        class="rounded-full bg-white text-[#0090FF] h-4 md:h-8 lg:h-10 w-auto flex items-center justify-center p-1 md:p-2 shadow-md group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-full h-full" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2.5">
                            <path d="M5 12h14m-7-7 7 7-7 7" />
                        </svg>
                    </a>
                </div>
            </div>
            <div
                class="w-full h-full bg-[#F2F2F2] rounded-lg md:rounded-2xl relative flex overflow-hidden p-2 md:p-6 lg:p-8 group cursor-pointer transition-all duration-300 hover:shadow-lg">

                <div class="absolute w-full h-24 mb-4">
                    <img src="{{ asset('assets/png/contact.png') }}" alt="contact us"
                        class="absolute top-0 md:-top-2 left-0 w-9 md:w-20 lg:w-32 h-auto opacity-90 transition-all duration-500 ease-out group-hover:scale-110 group-hover:-rotate-3">
                </div>

                <div class="w-full flex justify-between items-end">
                    <h2 class="text-sm md:text-2xl lg:text-4xl text-black font-bold leading-none tracking-tight">
                        Contact Us
                    </h2>

                    <a href="#"
                        class="rounded-full bg-white text-[#0090FF] h-4 md:h-8 lg:h-10 w-auto flex items-center justify-center p-1 md:p-2 shadow-md group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-full h-full" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2.5">
                            <path d="M5 12h14m-7-7 7 7-7 7" />
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
