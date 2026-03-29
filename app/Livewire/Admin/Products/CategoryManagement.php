<?php

namespace App\Livewire\Admin\Products;

use Livewire\Component;
use App\Models\Category;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Illuminate\Support\Str;

class CategoryManagement extends Component
{
    use WithPagination;

    public $showModal = false;
    public $isEditing = false;
    public $categoryId;
    
    public $name;
    public $icon;

    public function create()
    {
        $this->resetFields();
        $this->showModal = true;
    }

    public function edit($id)
    {
        $this->resetFields();
        $category = Category::findOrFail($id);
        $this->categoryId = $id;
        $this->name = $category->name;
        $this->icon = $category->icon;

        $this->isEditing = true;
        $this->showModal = true;
    }

    public function store()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'icon' => 'nullable|string|max:255',
        ]);

        if ($this->isEditing) {
            $category = Category::find($this->categoryId);
            $category->update([
                'name' => $this->name,
                'slug' => Str::slug($this->name),
                'icon' => $this->icon,
            ]);
        } else {
            Category::create([
                'name' => $this->name,
                'slug' => Str::slug($this->name),
                'icon' => $this->icon,
            ]);
        }

        $this->showModal = false;
        $this->resetFields();
        
        $this->dispatch('toast', 
            title: 'Berhasil', 
            message: 'Data kategori berhasil disimpan.', 
            type: 'success'
        );
    }
    
    public function confirmDelete($id)
    {
        $category = Category::find($id);
        if ($category->products()->count() > 0) {
            $this->dispatch('toast', title: 'Terikat Data', message: 'Kategori ini digunakan oleh produk. Tidak bisa dihapus.', type: 'warning');
            return;
        }

        $this->dispatch(
            'show-confirm',
            title: 'Hapus Kategori',
            message: 'Apakah Anda yakin ingin menghapus kategori ' . $category->name . '?',
            confirmEvent: 'delete-category',
            confirmParams: [$id],
            type: 'danger',
            confirmText: 'Hapus',
            cancelText: 'Batal',
        );
    }

    #[On('delete-category')]
    public function deleteCategory($id)
    {
        Category::find($id)?->delete();
        $this->dispatch('toast', title: 'Terhapus', message: 'Kategori berhasil dihapus permanen.', type: 'info');
    }

    public function resetFields()
    {
        $this->name = '';
        $this->icon = '';
        $this->categoryId = null;
        $this->isEditing = false;
    }

    #[Layout('layouts.admin')]
    public function render()
    {
        $categories = Category::withCount('products')->orderBy('name')->paginate(10);
        
        return view('livewire.admin.products.category-management', [
            'categories' => $categories
        ]);
    }
}
