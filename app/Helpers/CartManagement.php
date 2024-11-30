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

    static public function getCartItem($user_id, $product_id)
    {
        return CartItem::where('user_id', $user_id)
            ->where('product_id', $product_id)
            ->first();
    }


    static public function addItemToCart($user_id, $product_id, $qty = 1)
    {
        $product = Product::findOrFail($product_id);

        // Ambil jumlah produk yang sudah ada di keranjang
        $cart_item = CartItem::where('user_id', $user_id)
            ->where('product_id', $product_id)
            ->first();

        // Hitung total kuantitas (produk yang ada di keranjang + yang akan ditambahkan)
        $total_quantity = $cart_item ? $cart_item->quantity + $qty : $qty;

        // Cek apakah total kuantitas melebihi stok yang tersedia
        if ($total_quantity > $product->in_stock) {
            return response()->json(['message' => 'Jumlah yang diminta melebihi stok yang tersedia'], 400);
        }

        if ($cart_item) {
            // Jika produk sudah ada di keranjang, update kuantitas dan total harga
            $cart_item->quantity += $qty;
            $cart_item->total_amount = $cart_item->quantity * $cart_item->unit_amount;
            $cart_item->save();
        } else {
            // Jika produk belum ada di keranjang, buat item baru
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
            $product = $cart_item->product;  // Ambil produk terkait dari keranjang
            $new_quantity = $cart_item->quantity + 1;

            // Cek apakah stok mencukupi untuk jumlah kuantitas baru
            if ($new_quantity > $product->in_stock) {
                return response()->json(['message' => 'Jumlah kuantitas melebihi stok yang tersedia'], 400);
            }

            // Jika stok mencukupi, increment kuantitas
            $cart_item->quantity = $new_quantity;
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
