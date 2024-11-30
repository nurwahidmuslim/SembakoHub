<?php

namespace App\Livewire;

use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Attributes\Title;
use Livewire\Component;
use App\Models\Product;
use App\Helpers\CartManagement;

#[Title("Product Detail - SembakoHub")]
class ProductDetailPage extends Component
{
    use LivewireAlert;

    public $slug;
    public $quantity = 1;

    public function mount($slug)
    {
        $this->slug = $slug;
    }

    public function increaseQty()
    {
        $this->quantity++;
    }

    public function decreaseQty()
    {
        if ($this->quantity > 1) {
            $this->quantity--;
        }
    }

    // Tambahkan produk ke keranjang
    public function addToCart($product_id)
    {
        $user_id = auth()->id();

        if (!$user_id) {
            $this->alert('error', 'Anda harus masuk untuk menambahkan produk ke keranjang.', [
                'position' => 'bottom-end',
                'timer' => 3000,
                'toast' => true,
            ]);
            return;
        }

        // Cek apakah kuantitas yang diminta melebihi stok yang tersedia
        $product = Product::findOrFail($product_id);

        // Hitung total kuantitas termasuk yang ada di keranjang
        $cart_item = CartManagement::getCartItemsForUser($user_id)->where('product_id', $product_id)->first();
        $total_quantity = $cart_item ? $cart_item->quantity + $this->quantity : $this->quantity;

        if ($total_quantity > $product->in_stock) {
            $this->alert('error', 'Jumlah yang diminta melebihi stok yang tersedia.', [
                'position' => 'bottom-end',
                'timer' => 3000,
                'toast' => true,
            ]);
            return;
        }

        // Menambahkan produk ke keranjang
        CartManagement::addItemToCart($user_id, $product_id, $this->quantity);

        // Perbarui hitungan keranjang di navbar
        $total_count = CartManagement::getCartItemsCount($user_id);
        $this->dispatch('update-cart-count', $total_count);

        $this->alert('success', 'Produk berhasil ditambahkan ke keranjang!', [
            'position' => 'bottom-end',
            'timer' => 3000,
            'toast' => true,
        ]);
    }

    public function render()
    {
        return view('livewire.product-detail-page', [
            'product' => Product::where('slug', $this->slug)->firstOrFail()
        ]);
    }
}
