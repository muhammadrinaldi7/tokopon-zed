<?php

namespace App\Livewire\Admin\Products;

use Livewire\Component;
use App\Models\Product;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;

class ProductManagement extends Component
{
    use WithPagination;

    public $showModal = false;
    public $showDetailModal = false;
    public $isEditing = false;
    public $productId;
    public $detailProduct = null;
    
    public $name;
    public $description;
    public $specifications = [];
    public $categoryId;
    public $brandId;

    public function addSpecification()
    {
        $this->specifications[] = ['key' => '', 'value' => ''];
    }

    public function removeSpecification($index)
    {
        unset($this->specifications[$index]);
        $this->specifications = array_values($this->specifications);
    }

    public function create()
    {
        $this->resetFields();
        $this->showModal = true;
    }

    public function viewDetail($id)
    {
        $this->detailProduct = Product::with(['category', 'brand'])->find($id);
        $this->showDetailModal = true;
    }

    public function edit($id)
    {
        $this->resetFields();
        $product = Product::findOrFail($id);
        $this->productId = $id;
        $this->name = $product->name;
        $this->description = $product->description;
        $this->categoryId = $product->category_id;
        $this->brandId = $product->brand_id;
        
        // Format saved dict to array of key-value pairs for UI
        if (is_array($product->specifications)) {
            foreach ($product->specifications as $key => $value) {
                $this->specifications[] = ['key' => $key, 'value' => $value];
            }
        }

        $this->isEditing = true;
        $this->showModal = true;
    }

    public function store()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'categoryId' => 'required|exists:categories,id',
            'brandId' => 'nullable|exists:brands,id',
            'specifications.*.key' => 'required|string',
            'specifications.*.value' => 'required|string',
        ]);

        // Transform array back to dictionary for JSON storage
        $specsDict = [];
        foreach ($this->specifications as $spec) {
            if (!empty(trim($spec['key'])) && !empty(trim($spec['value']))) {
                $specsDict[trim($spec['key'])] = trim($spec['value']);
            }
        }

        if ($this->isEditing) {
            $product = Product::find($this->productId);
            $product->update([
                'name' => $this->name,
                'slug' => \Illuminate\Support\Str::slug($this->name) . '-' . time(),
                'description' => $this->description,
                'category_id' => $this->categoryId,
                'brand_id' => empty($this->brandId) ? null : $this->brandId,
                'specifications' => empty($specsDict) ? null : $specsDict,
            ]);
        } else {
            Product::create([
                'name' => $this->name,
                'slug' => \Illuminate\Support\Str::slug($this->name) . '-' . time(),
                'description' => $this->description,
                'category_id' => $this->categoryId,
                'brand_id' => empty($this->brandId) ? null : $this->brandId,
                'specifications' => empty($specsDict) ? null : $specsDict,
                'is_active' => true,
            ]);
        }

        $this->showModal = false;
        $this->resetFields();
        $this->dispatch('toast', title: 'Berhasil', message: 'Produk berhasil disimpan.', type: 'success');
    }

    public function delete($id)
    {
        Product::find($id)?->delete();
        $this->dispatch('toast', title: 'Terhapus', message: 'Produk berhasil dihapus permanen.', type: 'info');
    }

    public function resetFields()
    {
        $this->name = '';
        $this->description = '';
        $this->productId = null;
        $this->specifications = [];
        $this->categoryId = null;
        $this->brandId = null;
        $this->isEditing = false;
    }

    #[Layout('layouts.admin')]
    public function render()
    {
        $products = Product::with(['category', 'brand'])->orderByDesc('id')->paginate(10);
        $categoriesList = \App\Models\Category::orderBy('name')->get();
        $brandsList = \App\Models\Brand::orderBy('name')->get();
        
        return view('livewire.admin.products.product-management', [
            'products' => $products,
            'categoriesList' => $categoriesList,
            'brandsList' => $brandsList,
        ]);
    }
}
