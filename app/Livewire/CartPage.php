<?php

namespace App\Livewire;

use Livewire\Attributes\Title;
use Livewire\Component;
use App\Helpers\CartManagement;

#[Title("Cart - SembakoHub")]
class CartPage extends Component
{
    public $cart_items = [];
    public $grand_total;

    public function mount()
    {
        $user_id = auth()->id(); // Ambil ID pengguna login
        $this->cart_items = CartManagement::getCartItemsForUser($user_id);
        $this->grand_total = CartManagement::calculateGrandTotal($user_id);
    }

    public function addItem($product_id, $qty = 1)
    {
        $user_id = auth()->id();
        $this->cart_items = CartManagement::addItemToCart($user_id, $product_id, $qty);
        $this->grand_total = CartManagement::calculateGrandTotal($user_id);

        // Dispatch event untuk memperbarui jumlah item di navbar
        $total_count = CartManagement::getCartItemsCount($user_id);
        $this->dispatch('update-cart-count', $total_count);
    }

    public function removeItem($product_id)
    {
        $user_id = auth()->id();
        $this->cart_items = CartManagement::removeCartItem($user_id, $product_id);
        $this->grand_total = CartManagement::calculateGrandTotal($user_id);

        // Dispatch event untuk memperbarui jumlah item di navbar
        $total_count = CartManagement::getCartItemsCount($user_id);
        $this->dispatch('update-cart-count', $total_count);
    }

    public function increaseQty($product_id)
    {
        $user_id = auth()->id();
        $this->cart_items = CartManagement::incrementQuantityToCartItem($user_id, $product_id);
        $this->grand_total = CartManagement::calculateGrandTotal($user_id);

        // Dispatch event untuk memperbarui jumlah item di navbar
        $total_count = CartManagement::getCartItemsCount($user_id);
        $this->dispatch('update-cart-count', $total_count);
    }

    public function decreaseQty($product_id)
    {
        $user_id = auth()->id();
        $this->cart_items = CartManagement::decrementQuantityToCartItem($user_id, $product_id);
        $this->grand_total = CartManagement::calculateGrandTotal($user_id);

        // Dispatch event untuk memperbarui jumlah item di navbar
        $total_count = CartManagement::getCartItemsCount($user_id);
        $this->dispatch('update-cart-count', $total_count);
    }

    public function clearCart()
    {
        $user_id = auth()->id();
        CartManagement::clearCart($user_id);
        $this->cart_items = [];
        $this->grand_total = 0;

        // Dispatch event untuk memperbarui jumlah item di navbar
        $this->dispatch('update-cart-count', 0);
    }

    public function render()
    {
        return view('livewire.cart-page', [
            'cart_items' => $this->cart_items,
            'grand_total' => $this->grand_total,
        ]);
    }
}
