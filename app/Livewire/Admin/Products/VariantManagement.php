<?php

namespace App\Livewire\Admin\Products;

use Livewire\Component;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\ProductErzap;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;

class VariantManagement extends Component
{
    public Product $product;
    public $variants;

    // Form inputs
    public $ram;
    public $storage;
    public $color;
    public $condition = 'Baru';
    public $sku;

    // Autocomplete for Erzap
    public $searchErzap = '';
    public $selectedErzapId = null;
    public $selectedKode = null;
    public $searchResults = [];
    public $simulatedPrice = 0;
    public $simulatedStock = 0;

    public $isEditing = false;
    public $editingVariantId = null;

    public function mount(Product $product)
    {
        $this->product = $product;
        $this->loadVariants();
    }

    public function loadVariants()
    {
        $this->variants = $this->product->variants()->with('erzapData')->get();
    }

    public function updatedSearchErzap()
    {
        if (strlen($this->searchErzap) > 2) {
            $this->searchResults = ProductErzap::where(function ($query) {
                    $query->where('name', 'like', '%' . $this->searchErzap . '%')
                        ->orWhere('erzap_id', 'like', '%' . $this->searchErzap . '%');
                })
                ->doesntHave('variants')
                ->take(5)
                ->get();
        } else {
            $this->searchResults = [];
        }
    }

    public function selectErzap($erzapId, $price, $stock, $kode = null)
    {
        $this->selectedErzapId = $erzapId;
        $this->selectedKode = $kode;
        $this->searchErzap = $kode ? $kode . ' - ' . $erzapId : $erzapId;
        $this->simulatedPrice = $price;
        $this->simulatedStock = $stock;
        $this->searchResults = []; // close dropdown
    }

    public function clearErzap()
    {
        $this->selectedErzapId = null;
        $this->selectedKode = null;
        $this->searchErzap = '';
        $this->simulatedPrice = 0;
        $this->simulatedStock = 0;
    }

    public function saveVariant()
    {
        $this->validate([
            'condition' => 'required',
            'ram' => 'nullable|string',
            'storage' => 'nullable|string',
            'color' => 'nullable|string',
            'sku' => 'nullable|string',
        ]);

        $isNew = false;
        if ($this->isEditing && $this->editingVariantId) {
            $variant = ProductVariant::find($this->editingVariantId);
            $variant->update([
                'erzap_item_id' => $this->selectedErzapId,
                'condition' => $this->condition,
                'ram' => $this->ram,
                'storage' => $this->storage,
                'color' => $this->color,
                'sku' => $this->sku,
                // Price & stock handle by observer mostly, but we set initial here
                'price' => $this->selectedErzapId ? $this->simulatedPrice : 0,
                'stock' => $this->selectedErzapId ? $this->simulatedStock : 0,
            ]);
        } else {
            // Create
            ProductVariant::create([
                'product_id' => $this->product->id,
                'erzap_item_id' => $this->selectedErzapId,
                'condition' => $this->condition,
                'ram' => $this->ram,
                'storage' => $this->storage,
                'color' => $this->color,
                'sku' => $this->sku,
                'price' => $this->selectedErzapId ? $this->simulatedPrice : 0,
                'stock' => $this->selectedErzapId ? $this->simulatedStock : 0,
            ]);
            $isNew = true;
        }

        // Trigger manual update to ensure parent product gets re-calculated 
        // if this was the first active erzap variant.
        $this->triggerObserverCalculation();

        $this->resetForm();
        $this->loadVariants();

        $this->dispatch(
            'toast',
            title: 'Berhasil',
            message: $isNew ? 'Varian baru berhasil ditambahkan.' : 'Perubahan varian berhasil disimpan!',
            type: 'success'
        );
    }

    private function triggerObserverCalculation()
    {
        $variants = $this->product->variants()->get();
        $totalStock = $variants->sum('stock');
        $startingPrice = $variants->where('price', '>', 0)->min('price');
        $hasActiveErzap = $variants->whereNotNull('erzap_item_id')->count() > 0;

        $this->product->update([
            'total_stock' => $totalStock,
            'starting_price' => $startingPrice,
            'has_active_erzap' => $hasActiveErzap,
        ]);
    }

    public function editVariant($id)
    {
        $variant = ProductVariant::find($id);
        if ($variant) {
            $this->isEditing = true;
            $this->editingVariantId = $id;
            $this->condition = $variant->condition;
            $this->ram = $variant->ram;
            $this->storage = $variant->storage;
            $this->color = $variant->color;
            $this->sku = $variant->sku;

            if ($variant->erzap_item_id) {
                $erzap = $variant->erzapData;
                if ($erzap) {
                    $kode = $erzap->raw_data['kode'] ?? null;
                    $this->selectErzap($erzap->erzap_id, $variant->price, $variant->stock, $kode);
                }
            }
        }
    }

    public function confirmDelete($id)
    {
        $productDelete = ProductVariant::with('product')->find($id);
        $this->dispatch(
            'show-confirm',
            title: 'Hapus Varian',
            message: 'Apakah Anda yakin ingin menghapus varian ' . $productDelete->product->description . '?',
            confirmEvent: 'delete-variant',
            confirmParams: [$id],
            type: 'danger',
            confirmText: 'Hapus',
            cancelText: 'Batal',
        );
    }

    #[On('delete-variant')]
    public function deleteVariant($id)
    {
        ProductVariant::find($id)?->delete();
        $this->triggerObserverCalculation();
        $this->loadVariants();

        $this->dispatch(
            'toast',
            title: 'Terhapus',
            message: 'Varian produk berhasil dihapus dari sistem.',
            type: 'info'
        );
    }

    public function resetForm()
    {
        $this->isEditing = false;
        $this->editingVariantId = null;
        $this->condition = 'Baru';
        $this->ram = '';
        $this->storage = '';
        $this->color = '';
        $this->sku = '';
        $this->clearErzap();
    }

    #[Layout('layouts.admin')]
    public function render()
    {
        return view('livewire.admin.products.variant-management');
    }
}
