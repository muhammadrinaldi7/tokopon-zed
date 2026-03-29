<?php

namespace App\Livewire\Admin\Products;

use Livewire\Component;
use App\Models\Brand;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Illuminate\Support\Str;

class BrandManagement extends Component
{
    use WithPagination;

    public $showModal = false;
    public $isEditing = false;
    public $brandId;
    
    public $name;
    public $logo;

    public function create()
    {
        $this->resetFields();
        $this->showModal = true;
    }

    public function edit($id)
    {
        $this->resetFields();
        $brand = Brand::findOrFail($id);
        $this->brandId = $id;
        $this->name = $brand->name;
        $this->logo = $brand->logo;

        $this->isEditing = true;
        $this->showModal = true;
    }

    public function store()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'logo' => 'nullable|string|max:255',
        ]);

        if ($this->isEditing) {
            $brand = Brand::find($this->brandId);
            $brand->update([
                'name' => $this->name,
                'slug' => Str::slug($this->name),
                'logo' => $this->logo,
            ]);
        } else {
            Brand::create([
                'name' => $this->name,
                'slug' => Str::slug($this->name),
                'logo' => $this->logo,
            ]);
        }

        $this->showModal = false;
        $this->resetFields();
        
        $this->dispatch('toast', 
            title: 'Berhasil', 
            message: 'Data merek berhasil disimpan.', 
            type: 'success'
        );
    }
    
    public function confirmDelete($id)
    {
        $brand = Brand::find($id);
        if ($brand->products()->count() > 0) {
            $this->dispatch('toast', title: 'Terikat Data', message: 'Merek ini digunakan oleh produk. Tidak bisa dihapus.', type: 'warning');
            return;
        }

        $this->dispatch(
            'show-confirm',
            title: 'Hapus Merek',
            message: 'Apakah Anda yakin ingin menghapus merek ' . $brand->name . '?',
            confirmEvent: 'delete-brand',
            confirmParams: [$id],
            type: 'danger',
            confirmText: 'Hapus',
            cancelText: 'Batal',
        );
    }

    #[On('delete-brand')]
    public function deleteBrand($id)
    {
        Brand::find($id)?->delete();
        $this->dispatch('toast', title: 'Terhapus', message: 'Merek berhasil dihapus permanen.', type: 'info');
    }

    public function resetFields()
    {
        $this->name = '';
        $this->logo = '';
        $this->brandId = null;
        $this->isEditing = false;
    }

    #[Layout('layouts.admin')]
    public function render()
    {
        $brands = Brand::withCount('products')->orderBy('name')->paginate(10);
        
        return view('livewire.admin.products.brand-management', [
            'brands' => $brands
        ]);
    }
}
