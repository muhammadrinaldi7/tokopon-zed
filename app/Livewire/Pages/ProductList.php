<?php

namespace App\Livewire\Pages;

use Livewire\Component;
use App\Models\Product;
use App\Models\Category;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;

class ProductList extends Component
{
    use WithPagination;

    public $search = '';
    public $selectedCategory = null;

    protected $queryString = [
        'search' => ['except' => ''],
        'selectedCategory' => ['except' => null, 'as' => 'category'],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingSelectedCategory()
    {
        $this->resetPage();
    }

    public function selectCategory($categoryId)
    {
        if ($this->selectedCategory == $categoryId) {
            $this->selectedCategory = null;
        } else {
            $this->selectedCategory = $categoryId;
        }
        $this->resetPage();
    }

    #[Layout('layouts.app', ['title' => 'Buy Mobile Phones - TokoPun'])]
    public function render()
    {
        $categories = Category::orderBy('name')->get();

        $query = Product::with(['category', 'brand', 'media'])
            ->availableForCustomer();

        if ($this->search) {
            $query->where('name', 'like', '%' . $this->search . '%');
        }

        if ($this->selectedCategory) {
            $query->where('category_id', $this->selectedCategory);
        }

        $products = $query->orderByDesc('id')->paginate(12);

        return view('livewire.pages.product-list', [
            'categories' => $categories,
            'products' => $products
        ]);
    }
}
