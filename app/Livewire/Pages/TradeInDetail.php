<?php

namespace App\Livewire\Pages;

use App\Models\TradeIn;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

class TradeInDetail extends Component
{
    public TradeIn $tradeIn;
    public string $customerShippingReceipt = '';

    public function mount(TradeIn $tradeIn)
    {
        if ($tradeIn->user_id !== Auth::id()) {
            abort(403);
        }
        $this->tradeIn = $tradeIn->load(['targetProduct.media', 'unitOptions.variant', 'media']);
        $this->customerShippingReceipt = $tradeIn->customer_shipping_receipt ?? '';
    }

    public function selectVariant($variantId)
    {
        if ($this->tradeIn->status !== 'OFFERED') {
            return;
        }

        // Tandai opsi yang dipilih
        foreach ($this->tradeIn->unitOptions as $option) {
            if ($option->product_variant_id == $variantId) {
                $option->update(['is_selected' => true]);
            } else {
                $option->update(['is_selected' => false]);
            }
        }

        if ($this->tradeIn->customer_shipping_receipt) {
            // Jika ini adalah revisi (barang sudah di toko / resi sudah ada)
            $this->tradeIn->update(['status' => 'INSPECTING']);
            $this->tradeIn->refresh();
            $this->dispatch('toast', title: 'Penawaran Disetujui', message: 'Anda telah menyetujui harga revisi. Menunggu tagihan akhir.', type: 'success');
        } else {
            // Alur normal pertama kali
            $this->tradeIn->update(['status' => 'WAITING_FOR_DEVICE']);
            $this->tradeIn->refresh();
            $this->dispatch('toast', title: 'Berhasil Memilih', message: 'Silakan kirimkan unit HP lama Anda ke kurir ekspedisi.', type: 'success');
        }
    }

    public function cancel()
    {
        if (!in_array($this->tradeIn->status, ['PENDING', 'OFFERED'])) {
            return;
        }
        
        $this->tradeIn->update(['status' => 'CANCELLED']);
        $this->dispatch('toast', title: 'Dibatalkan', message: 'Tukar tambah berhasil dibatalkan.', type: 'info');
    }

    public function submitReceipt()
    {
        $this->validate(['customerShippingReceipt' => 'required|string|max:100']);
        
        $this->tradeIn->update([
            'customer_shipping_receipt' => $this->customerShippingReceipt,
            'status' => 'INSPECTING' // Beritahu Admin tiket ini sudah dikirim fisiknya
        ]);
        
        $this->dispatch('toast', title: 'Resi Terkirim', message: 'Manajer Cabang akan memvalidasi fisik HP Anda setelah barang tiba.', type: 'success');
    }

    #[Layout('layouts.app', ['title' => 'Detail Tukar Tambah'])]
    public function render()
    {
        return view('livewire.pages.trade-in-detail');
    }
}
