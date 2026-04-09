<div class="relative" x-data="{ justAdded: @entangle('added') }"
    x-effect="if(justAdded) { setTimeout(() => { justAdded = false; $wire.set('added', false) }, 2000) }">
    {{-- Main Add to Cart Button --}}
    <button wire:click="openVariantPicker" @class([
        'text-center text-sm md:text-base py-1 md:py-2 font-bold border rounded-full w-full hover:bg-black hover:text-white transition-all duration-300' => !$added,
    ]) wire:loading.attr="disabled">
        @if ($added)
            <div class="flex items-center justify-center gap-2">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                </svg>
                <span>Ditambahkan</span>
            </div>
        @else
            Buy Now
            {{-- Loading spinner --}}
            <svg class="w-5 h-5 animate-spin" wire:loading wire:target="openVariantPicker,addToCart" fill="none"
                viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                </circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
            </svg>
        @endif
    </button>

    {{-- Variant Picker Modal --}}
    @if ($showVariantPicker)
        <div class="fixed inset-0 z-90 flex items-end sm:items-center justify-center"
            wire:click.self="closeVariantPicker">
            {{-- Backdrop --}}
            <div class="fixed inset-0 bg-black/50 backdrop-blur-sm" wire:click="closeVariantPicker"></div>

            {{-- Modal Content --}}
            <div
                class="relative bg-white w-full sm:max-w-md sm:rounded-2xl rounded-t-2xl shadow-2xl z-10 max-h-[80vh] overflow-y-auto">
                {{-- Header --}}
                <div
                    class="sticky top-0 bg-white px-6 py-4 border-b border-gray-100 flex items-center justify-between rounded-t-2xl">
                    <div>
                        <h3 class="font-bold text-gray-800">Pilih Varian</h3>
                        <p class="text-xs text-gray-400 mt-0.5">{{ $product->name }}</p>
                    </div>
                    <button wire:click="closeVariantPicker" class="text-gray-400 hover:text-gray-600 p-1">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                {{-- Variants List --}}
                <div class="px-6 py-4 space-y-3">
                    @foreach ($product->variants->where('stock', '>', 0) as $variant)
                        <button wire:click="addToCart({{ $variant->id }})"
                            class="w-full text-left p-4 rounded-xl border-2 border-gray-100 hover:border-[#4E44DB] hover:bg-[#4E44DB]/5 transition-all group">
                            <div class="flex items-center justify-between">
                                <div>
                                    <div class="flex flex-wrap gap-1.5 mb-1.5">
                                        @if ($variant->condition)
                                            <span
                                                class="text-[10px] font-semibold px-2 py-0.5 rounded-md bg-gray-100 text-gray-600">{{ $variant->condition }}</span>
                                        @endif
                                        @if ($variant->color)
                                            <span
                                                class="text-[10px] font-semibold px-2 py-0.5 rounded-md bg-blue-50 text-blue-600">{{ $variant->color }}</span>
                                        @endif
                                        @if ($variant->storage)
                                            <span
                                                class="text-[10px] font-semibold px-2 py-0.5 rounded-md bg-purple-50 text-purple-600">{{ $variant->storage }}</span>
                                        @endif
                                        @if ($variant->ram)
                                            <span
                                                class="text-[10px] font-semibold px-2 py-0.5 rounded-md bg-emerald-50 text-emerald-600">{{ $variant->ram }}
                                                RAM</span>
                                        @endif
                                    </div>
                                    <p class="font-bold text-gray-800 group-hover:text-[#4E44DB] transition">
                                        Rp {{ number_format($variant->price, 0, ',', '.') }}
                                    </p>
                                    <p class="text-[10px] text-gray-400 mt-0.5">Stok: {{ $variant->stock }}</p>
                                </div>
                                <div class="text-gray-300 group-hover:text-[#4E44DB] transition">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                        stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z" />
                                    </svg>
                                </div>
                            </div>
                        </button>
                    @endforeach
                </div>
            </div>
        </div>
    @endif
</div>
