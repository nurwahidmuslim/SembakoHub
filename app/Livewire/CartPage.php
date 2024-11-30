<?php

namespace App\Livewire;

use Livewire\Attributes\Title;
use Livewire\Component;
use App\Helpers\CartManagement;
use Jantinnerezo\LivewireAlert\LivewireAlert;

#[Title("Keranjang - SembakoHub")]
class CartPage extends Component
{
    use LivewireAlert; // Menggunakan trait untuk alert

    public $cart_items = []; // Menyimpan item di keranjang
    public $grand_total; // Total harga dari semua item di keranjang

    // Fungsi untuk menyiapkan data saat halaman pertama kali dimuat
    public function mount()
    {
        $user_id = auth()->id(); // Mengambil ID pengguna yang sedang login
        $this->cart_items = CartManagement::getCartItemsForUser($user_id); // Mengambil item keranjang untuk pengguna
        $this->grand_total = CartManagement::calculateGrandTotal($user_id); // Menghitung total harga
    }

    // Fungsi untuk menambahkan item ke keranjang
    public function addItem($product_id, $qty = 1)
    {
        $user_id = auth()->id(); // Mengambil ID pengguna yang sedang login
        $product = CartManagement::addItemToCart($user_id, $product_id, $qty); // Menambahkan produk ke keranjang

        if ($product instanceof \Illuminate\Http\Response) {
            // Jika terjadi error (misalnya stok tidak cukup)
            $this->alert('error', $product->getData()->message, [
                'position' => 'bottom-end',
                'timer' => 3000,
                'toast' => true,
            ]);
            return;
        }

        $this->updateCartData(); // Memperbarui data keranjang setelah item ditambahkan
        $this->alert('success', 'Item added to cart successfully!', [
            'position' => 'bottom-end',
            'timer' => 3000,
            'toast' => true,
        ]);
    }

    // Fungsi untuk menghapus item dari keranjang
    public function removeItem($product_id)
    {
        $user_id = auth()->id(); // Mengambil ID pengguna yang sedang login
        $this->cart_items = CartManagement::removeCartItem($user_id, $product_id); // Menghapus item dari keranjang
        $this->grand_total = CartManagement::calculateGrandTotal($user_id); // Menghitung total harga setelah item dihapus

        $this->updateCartCount(); // Memperbarui jumlah item di navbar
        $this->alert('success', 'Item removed from cart!', [
            'position' => 'bottom-end',
            'timer' => 3000,
            'toast' => true,
        ]);
    }

    // Fungsi untuk menambah jumlah item di keranjang
    public function increaseQty($product_id)
    {
        $user_id = auth()->id();
        
        // Mengambil item keranjang
        $item = CartManagement::getCartItem($user_id, $product_id);
        if (!$item) {
            $this->alert('error', 'Item not found in the cart.', [
                'position' => 'bottom-end',
                'timer' => 3000,
                'toast' => true,
            ]);
            return;
        }
        
        // Mendapatkan produk dan stoknya
        $product = $item->product;
        $current_qty = $item->quantity;
        $stock = $product->in_stock;

        // Memeriksa ketersediaan stok
        if ($current_qty < $stock) {
            // Menambah kuantitas jika stok mencukupi
            $this->cart_items = CartManagement::incrementQuantityToCartItem($user_id, $product_id);
            $this->grand_total = CartManagement::calculateGrandTotal($user_id); // Menghitung ulang total harga
            $this->updateCartCount(); // Memperbarui jumlah item di navbar
        } else {
            // Menampilkan pesan jika stok tidak mencukupi
            $this->alert('error', 'Stok tidak cukup untuk menambah jumlah produk ini.', [
                'position' => 'bottom-end',
                'timer' => 3000,
                'toast' => true,
            ]);
        }
    }

    // Fungsi untuk mengurangi jumlah item di keranjang
    public function decreaseQty($product_id)
    {
        $user_id = auth()->id();
        $this->cart_items = CartManagement::decrementQuantityToCartItem($user_id, $product_id); // Mengurangi kuantitas item
        $this->grand_total = CartManagement::calculateGrandTotal($user_id); // Menghitung ulang total harga
        $this->updateCartCount(); // Memperbarui jumlah item di navbar
    }

    // Fungsi untuk membersihkan seluruh keranjang
    public function clearCart()
    {
        $user_id = auth()->id();
        CartManagement::clearCart($user_id); // Menghapus semua item dari keranjang
        $this->cart_items = []; // Mengosongkan daftar item keranjang
        $this->grand_total = 0; // Mengatur total harga menjadi 0

        $this->dispatch('update-cart-count', 0); // Memperbarui jumlah item di navbar
    }

    // Fungsi pembantu untuk memperbarui data keranjang dan menghitung total harga
    private function updateCartData()
    {
        $user_id = auth()->id();
        $this->cart_items = CartManagement::getCartItemsForUser($user_id); // Mengambil data item keranjang
        $this->grand_total = CartManagement::calculateGrandTotal($user_id); // Menghitung total harga

        $this->updateCartCount(); // Memperbarui jumlah item di navbar
    }

    // Fungsi pembantu untuk memperbarui jumlah item keranjang di navbar
    private function updateCartCount()
    {
        $user_id = auth()->id();
        $total_count = CartManagement::getCartItemsCount($user_id); // Mengambil jumlah total item di keranjang
        $this->dispatch('update-cart-count', $total_count); // Mengirimkan event untuk memperbarui jumlah item
    }

    // Fungsi untuk merender tampilan halaman keranjang
    public function render()
    {
        return view('livewire.cart-page', [
            'cart_items' => $this->cart_items,
            'grand_total' => $this->grand_total,
        ]);
    }
}
