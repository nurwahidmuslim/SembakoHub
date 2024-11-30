<?php

namespace App\Livewire;

use App\Models\Category;
use App\Helpers\CartManagement;
use App\Livewire\Partials\Navbar;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use App\Models\Product;
use Livewire\WithPagination;
use App\Models\Brand;

#[Title("Products - SembakoHub")]
class ProductsPage extends Component
{
    use WithPagination;
    use LivewireAlert;

    #[Url]
    public $selected_categories = [];

    #[Url]
    public $selected_brands = [];

    #[Url]
    public $sort = "latest";

    public $quantity = 1;

    // Tambahkan produk ke keranjang
    public function addToCart($product_id)
    {
        $user_id = auth()->id(); // Pastikan pengguna login

        if (!$user_id) {
            $this->alert('error', 'Anda harus masuk untuk menambahkan produk ke keranjang.', [
                'position' => 'bottom-end',
                'timer' => 3000,
                'toast' => true,
            ]);
            return;
        }

        // Tambahkan produk ke keranjang
        $cart_items = CartManagement::addItemToCart($user_id, $product_id, $this->quantity);

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
        $productQuery = Product::where('is_active', 1); 
        
        if (!empty($this->selected_categories)) {
            $productQuery->whereIn('category_id', $this->selected_categories);
        }

        if (!empty($this->selected_brands)) {
            $productQuery->whereIn('brand_id', $this->selected_brands);
        }

        if ($this->sort == 'latest') {
            $productQuery->latest();
        }
        
        if ($this->sort == 'price') {
            $productQuery->orderBy('price');
        }
        
        return view('livewire.products-page', [
            'products' => $productQuery->paginate(6), 
            'brands' => Brand::where('is_active', 1)->get(['id', 'name', 'slug']),
            'categories' => Category::where('is_active', 1)->get(['id', 'name', 'slug']),
        ]);
    }
}
