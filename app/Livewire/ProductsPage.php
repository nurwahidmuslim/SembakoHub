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

        $product = Product::findOrFail($product_id);

        // Ambil jumlah produk yang sudah ada di keranjang
        $cart_item = CartManagement::getCartItemsForUser($user_id)
            ->where('product_id', $product_id)
            ->first();

        // Hitung total kuantitas yang ingin ditambahkan (kuantitas yang ada di keranjang + kuantitas yang ingin ditambahkan)
        $total_quantity = $cart_item ? $cart_item->quantity + $this->quantity : $this->quantity;

        // Cek apakah total kuantitas melebihi stok yang tersedia
        if ($total_quantity > $product->in_stock) {
            $this->alert('error', 'Jumlah yang diminta melebihi stok yang tersedia.', [
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

    // Fungsi untuk menambah kuantitas produk di keranjang
    public function incrementQuantityToCartItem($product_id)
    {
        $user_id = auth()->id(); // Pastikan pengguna login

        if (!$user_id) {
            $this->alert('error', 'Anda harus masuk untuk mengubah jumlah produk di keranjang.', [
                'position' => 'bottom-end',
                'timer' => 3000,
                'toast' => true,
            ]);
            return;
        }

        $cart_item = CartManagement::getCartItemsForUser($user_id)
            ->where('product_id', $product_id)
            ->first();

        if ($cart_item) {
            $product = $cart_item->product;
            $new_quantity = $cart_item->quantity + 1;

            // Cek apakah stok mencukupi untuk jumlah kuantitas baru
            if ($new_quantity > $product->in_stock) {
                $this->alert('error', 'Jumlah kuantitas melebihi stok yang tersedia.', [
                    'position' => 'bottom-end',
                    'timer' => 3000,
                    'toast' => true,
                ]);
                return;
            }

            // Increment kuantitas di keranjang
            CartManagement::incrementQuantityToCartItem($user_id, $product_id);
        }

        // Perbarui hitungan keranjang di navbar
        $total_count = CartManagement::getCartItemsCount($user_id);
        $this->dispatch('update-cart-count', $total_count);
    }

    public function render()
    {
        $productQuery = Product::where('in_stock', '>', 0); 
        
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
