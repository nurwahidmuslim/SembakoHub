<?php

namespace App\Helpers;

use App\Models\CartItem;
use App\Models\Product;

class CartManagement
{
    // Mendapatkan semua item keranjang untuk user tertentu
    static public function getCartItemsForUser($user_id)
    {
        return CartItem::where('user_id', $user_id)
            ->with('product') // Memuat relasi ke produk termasuk gambar
            ->get();
    }

    // Menambahkan item ke keranjang
    static public function addItemToCart($user_id, $product_id, $qty = 1)
    {
        $cart_item = CartItem::where('user_id', $user_id)
            ->where('product_id', $product_id)
            ->first();

        $product = Product::findOrFail($product_id);  // Menarik data produk untuk nama, harga, dan gambar

        if ($cart_item) {
            // Jika item sudah ada, tambahkan jumlah
            $cart_item->quantity += $qty;
            $cart_item->total_amount = $cart_item->quantity * $cart_item->unit_amount;
            $cart_item->save();
        } else {
            // Tambahkan item baru
            CartItem::create([
                'user_id' => $user_id,
                'product_id' => $product_id,
                'quantity' => $qty,
                'unit_amount' => $product->price,
                'total_amount' => $qty * $product->price,
            ]);
        }

        // Kembalikan daftar item keranjang beserta data produk yang relevan
        return self::getCartItemsForUser($user_id);
    }

    // Menghapus item dari keranjang
    static public function removeCartItem($user_id, $product_id)
    {
        CartItem::where('user_id', $user_id)
            ->where('product_id', $product_id)
            ->delete();

        return self::getCartItemsForUser($user_id);
    }

    // Menghitung total harga keranjang
    static public function calculateGrandTotal($user_id)
    {
        return CartItem::where('user_id', $user_id)->sum('total_amount');
    }

    // Menghapus semua item di keranjang
    static public function clearCart($user_id)
    {
        CartItem::where('user_id', $user_id)->delete();
    }

    // Menambahkan kuantitas item di keranjang
    static public function incrementQuantityToCartItem($user_id, $product_id)
    {
        $cart_item = CartItem::where('user_id', $user_id)
            ->where('product_id', $product_id)
            ->first();

        if ($cart_item) {
            $cart_item->quantity++;
            $cart_item->total_amount = $cart_item->quantity * $cart_item->unit_amount;
            $cart_item->save();
        }

        return self::getCartItemsForUser($user_id);
    }

    // Mengurangi kuantitas item di keranjang
    static public function decrementQuantityToCartItem($user_id, $product_id)
    {
        $cart_item = CartItem::where('user_id', $user_id)
            ->where('product_id', $product_id)
            ->first();

        if ($cart_item && $cart_item->quantity > 1) {
            $cart_item->quantity--;
            $cart_item->total_amount = $cart_item->quantity * $cart_item->unit_amount;
            $cart_item->save();
        }

        return self::getCartItemsForUser($user_id);
    }

    public static function getCartItemsCount($user_id)
    {
        return CartItem::where('user_id', $user_id)->sum('quantity');
    }
}
