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

    <livewire:pages.description />
    <livewire:pages.catalog />
</div>
