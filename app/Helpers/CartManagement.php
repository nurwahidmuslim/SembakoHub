<?php

namespace App\Helpers;

use App\Models\CartItem;
use App\Models\Product;

class CartManagement
{
    static public function getCartItemsForUser($user_id)
    {
        return CartItem::where('user_id', $user_id)
            ->with('product') // Memuat relasi ke produk termasuk gambar
            ->get();
    }

    static public function addItemToCart($user_id, $product_id, $qty = 1)
    {
        $cart_item = CartItem::where('user_id', $user_id)
            ->where('product_id', $product_id)
            ->first();

        $product = Product::findOrFail($product_id);

        if ($cart_item) {
            $cart_item->quantity += $qty;
            $cart_item->total_amount = $cart_item->quantity * $cart_item->unit_amount;
            $cart_item->save();
        } else {
            CartItem::create([
                'user_id' => $user_id,
                'product_id' => $product_id,
                'quantity' => $qty,
                'unit_amount' => $product->price,
                'total_amount' => $qty * $product->price,
            ]);
        }

        return self::getCartItemsForUser($user_id);
    }

    static public function removeCartItem($user_id, $product_id)
    {
        CartItem::where('user_id', $user_id)
            ->where('product_id', $product_id)
            ->delete();

        return self::getCartItemsForUser($user_id);
    }

    static public function calculateGrandTotal($user_id)
    {
        return CartItem::where('user_id', $user_id)->sum('total_amount');
    }

    static public function clearCart($user_id)
    {
        CartItem::where('user_id', $user_id)->delete();
    }

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

    static public function calculateTotalWeight($user_id)
    {
        $cart_items = self::getCartItemsForUser($user_id);
        $total_weight = 0;

        foreach ($cart_items as $item) {
            $product = $item->product;
            $product_weight = $product->weight ?? 0; // Ambil berat produk dari atribut `weight`
            $total_weight += $product_weight * $item->quantity;
        }

        return $total_weight;
    }
}
