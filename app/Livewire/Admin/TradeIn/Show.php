<?php

namespace App\Livewire\Admin\TradeIn;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderPayment;
use App\Models\ProductVariant;
use App\Models\TradeIn;
use App\Models\TradeInUnitOption;
use App\Services\XenditService;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class Show extends Component
{
    public TradeIn $tradeIn;
    
    // Appraisal Form
    public $appraisedValue = 0;
    public array $selectedVariants = [];
    public $searchVariant = '';
    
    // Physical Inspection Form
    public $shippingCost = 0;
    
    // Convert to Second Product
    public $convertModal = false;
    public $sellPrice = 0;
    public $secondCondition = 'Bekas';
    public $existingProductId = null; // Opsional: gabung ke parent "iPhone 13 Pro Max - Second" yang sudah ada

    public function mount(TradeIn $tradeIn)
    {
        $this->tradeIn = $tradeIn->load(['user', 'targetProduct', 'media', 'unitOptions.variant', 'buybackDevice.tier']);
        $this->appraisedValue = $this->tradeIn->appraised_value ?? 0;
        
        $this->selectedVariants = $this->tradeIn->product_variant_id ? [$this->tradeIn->product_variant_id] : [];
    }

    // submitAppraisal dihapus karena harga sudah fix dan admin langsung assign unit saat inspeksi
    
    public function toggleVariant($variantId)
    {
        $this->selectedVariants = [$variantId];
    }

    public function markAsPhysicallyVerified(XenditService $xendit)
    {
        if (!in_array($this->tradeIn->status, ['WAITING_FOR_DEVICE', 'INSPECTING'])) return;

        if (empty($this->selectedVariants)) {
            $this->dispatch('toast', title: 'Gagal', message: 'Silakan pilih 1 unit produk untuk diberikan ke pengguna.', type: 'error');
            return;
        }

        DB::transaction(function () use ($xendit) {
            $variantId = $this->selectedVariants[0];
            $variant = \App\Models\ProductVariant::find($variantId);
            $topupAmount = max(0, $variant->price - (float) $this->tradeIn->appraised_value);
            
            // Assign unit ke trade in
            $this->tradeIn->update([
                'product_variant_id' => $variant->id
            ]);

            // Build temporary Order
            $order = Order::create([
                'user_id' => $this->tradeIn->user_id,
                'order_number' => 'ORD-TRD-' . date('YmdHis') . rand(100, 999),
                'total_amount' => $topupAmount,
                'shipping_cost' => 0,
                'discount_amount' => 0,
                'grand_total' => $topupAmount,
                'order_status' => 'PENDING',
                'shipping_address_snapshot' => ['address' => 'Tukar Tambah Unit di Cabang', 'phone_number' => '0000', 'city' => '-', 'postal_code' => '0000'],
            ]);

            OrderItem::create([
                'order_id' => $order->id,
                'product_variant_id' => $variant->id,
                'qty' => 1,
                'price_at_checkout' => $variant->price,
                'subtotal' => $variant->price
            ]);
            
            // Deduct Stock immediately
            $variant->decrement('stock', 1);

            // Jika ada selisih, buat Invoice Xendit
            if ($topupAmount > 0) {
                // Buat invoice di Xendit
                $invoice = $xendit->createInvoice($order);

                // Buat OrderPayment
                OrderPayment::create([
                    'order_id' => $order->id,
                    'payment_method' => 'xendit',
                    'xendit_external_id' => $order->order_number,
                    'amount' => $topupAmount,
                    'status' => 'PENDING',
                    'xendit_invoice_url' => $invoice['invoice_url']
                ]);
                $this->tradeIn->update(['status' => 'PAYING', 'order_id' => $order->id]);
            } else {
                // Lunas karena harga sama atau appraise > harga unit incaran
                OrderPayment::create([
                    'order_id' => $order->id,
                    'payment_method' => 'wallet',
                    'xendit_external_id' => $order->order_number,
                    'amount' => 0,
                    'status' => 'PAID',
                ]);
                $order->update(['status' => 'COMPLETED']);
                $this->tradeIn->update(['status' => 'COMPLETED', 'order_id' => $order->id]);
            }
        });

        $this->tradeIn->refresh();
        $this->dispatch('toast', title: 'Fisik Diterima', message: 'Tagihan berhasil diterbitkan untuk pengguna.', type: 'success');
    }

    public function reject()
    {
        $this->tradeIn->update(['status' => 'CANCELLED']);
        $this->dispatch('toast', title: 'Ditolak', message: 'Tukar tambah dibatalkan secara sepihak.', type: 'info');
    }

    public function convertToProduct()
    {
        if ($this->tradeIn->status !== 'COMPLETED') return;

        $this->validate([
            'sellPrice' => 'required|numeric|min:1000',
            'secondCondition' => 'required|string',
        ]);

        DB::transaction(function () {
            // Cek apakah produk parent sudah pernah dibikin untuk merek/tipe ini (yang second)
            $productName = $this->tradeIn->old_phone_brand . ' ' . $this->tradeIn->old_phone_model;
            
            $product = null;
            if ($this->existingProductId) {
                $product = \App\Models\Product::find($this->existingProductId);
            } else {
                $product = \App\Models\Product::firstOrCreate(
                    ['name' => $productName, 'is_second' => true],
                    [
                        'slug' => Str::slug($productName . ' Second ' . rand(100, 999)),
                        'brand_id' => null, // Opsional jika punya relasi tabel brands
                        'category_id' => \App\Models\Category::first()?->id, // Default ke kategori pertama
                        'description' => 'Produk unit seken / bekas pakai.',
                        'is_active' => true,
                        'starting_price' => $this->sellPrice,
                    ]
                );
            }

            // Buat variant fisiknya
            ProductVariant::create([
                'product_id' => $product->id,
                'trade_in_id' => $this->tradeIn->id,
                'storage' => $this->tradeIn->old_phone_storage ?? '-',
                'color' => '-',
                'condition' => $this->secondCondition,
                'weight' => 500, // asumsikan
                'price' => $this->sellPrice,
                'stock' => 1,
            ]);
            
            // Tandai Trade In sudah memiliki produk / ter-convert (opsional, bisa dilacak dari trade_in_id di variants)
        });

        $this->convertModal = false;
        $this->dispatch('toast', title: 'Berhasil', message: 'Unit HP lama masuk ke Katalog Second.', type: 'success');
    }

    #[Layout('layouts.admin')]
    public function render()
    {
        $availableVariants = ProductVariant::with('product')
            ->where('product_id', $this->tradeIn->target_product_id)
            ->where('stock', '>', 0) /* Sesuai instruksi user bahwa 1 unit fisik adalah 1 product/variant */
            ->when($this->searchVariant, function($q) {
                $q->where('storage', 'like', "%{$this->searchVariant}%")
                  ->orWhere('color', 'like', "%{$this->searchVariant}%")
                  ->orWhere('condition', 'like', "%{$this->searchVariant}%");
            })
            ->get();

        return view('livewire.admin.trade-in.show', [
            'availableVariants' => $availableVariants
        ]);
    }
}
