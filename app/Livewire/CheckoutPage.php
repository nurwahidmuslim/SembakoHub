<?php

namespace App\Livewire;

use App\Models\Order;
use App\Models\Address;
use Livewire\Component;
use Midtrans\Config;
use GuzzleHttp\Client;
use App\Helpers\CartManagement;
use App\Mail\OrderPlaced;
use Illuminate\Support\Facades\Mail;

class CheckoutPage extends Component
{
    public $first_name;
    public $last_name;
    public $phone;
    public $street_address;
    public $city;
    public $state;
    public $payment_method;
    public $shipping_amount;
    public $shipping_method;
    public $weight;
    public $snap_token;

    public function mount()
    {
        $user_id = auth()->id();
        $cart_items = CartManagement::getCartItemsForUser($user_id);

        if ($cart_items->isEmpty()) {
            session()->flash('error', 'Keranjang Anda kosong.');
            return redirect()->route('products');
        }
    }

    public function placeOrder()
    {
        $this->validate([
            'first_name'    => 'required',
            'last_name'     => 'required',
            'phone'         => 'required',
            'street_address'=> 'required',
            'city'          => 'required',
            'state'         => 'required',
            'payment_method'=> 'required',
        ]);

        $user_id = auth()->id();
        $cart_items = CartManagement::getCartItemsForUser($user_id);

        if ($cart_items->isEmpty()) {
            session()->flash('error', 'Keranjang Anda kosong.');
            return redirect()->route('products');
        }

        $grand_total = CartManagement::calculateGrandTotal($user_id);

        // Membuat order baru
        $order = new Order();
        $order->user_id = $user_id;
        $order->grand_total = $grand_total;
        $order->payment_method = $this->payment_method;
        $order->payment_status = 'pending';
        $order->status = 'new';
        $order->shipping_amount = 0;
        $order->shipping_method = 'none';
        $order->notes = 'Pesanan atas nama ' . auth()->user()->name;
        $order->order_id = 'ORDER-' . uniqid();

        // Membuat alamat pengiriman
        $address = new Address();
        $address->fill([
            'first_name'    => $this->first_name,
            'last_name'     => $this->last_name,
            'phone'         => $this->phone,
            'street_address'=> $this->street_address,
            'city'          => $this->city,
            'state'         => $this->state,
        ]);

        // Proses pembayaran dengan Midtrans (jika pembayaran online)
        $redirect_url = '';
        if ($this->payment_method == 'online') {
            Config::$serverKey = env('MIDTRANS_SERVER_KEY');
            Config::$isProduction = false;
            Config::$isSanitized = true;
            Config::$is3ds = true;

            $transactionDetails = [
                'order_id' => $order->order_id,
                'gross_amount' => $grand_total,
            ];

            $customerDetails = [
                'first_name' => $this->first_name,
                'last_name' => $this->last_name,
                'email' => auth()->user()->email,
                'phone' => $this->phone,
            ];

            $itemDetails = $cart_items->map(function ($item) {
                return [
                    'id' => $item->product_id,
                    'price' => $item->unit_amount,
                    'quantity' => $item->quantity,
                    'name' => $item->product->name,
                ];
            })->toArray();

            $snapPayload = [
                'transaction_details' => $transactionDetails,
                'customer_details' => $customerDetails,
                'item_details' => $itemDetails,
                'callbacks' => [
                    'finish' => route('success'),
                ]
            ];

            try {
                $client = new Client();
                $response = $client->post('https://app.sandbox.midtrans.com/snap/v1/transactions', [
                    'headers' => [
                        'Authorization' => 'Basic ' . base64_encode(Config::$serverKey . ':'),
                        'Content-Type' => 'application/json',
                    ],
                    'json' => $snapPayload,
                ]);

                $responseBody = json_decode($response->getBody(), true);
                $this->snap_token = $responseBody['token'] ?? null;

                if ($this->snap_token) {
                    $order->snap_token = $this->snap_token;
                    $redirect_url = $responseBody['redirect_url'];
                } else {
                    throw new \Exception('Token transaksi tidak tersedia dalam respons Midtrans.');
                }
            } catch (\Exception $e) {
                session()->flash('error', 'Gagal membuat transaksi dengan Midtrans: ' . $e->getMessage());
                return redirect()->route('checkout');
            }
        } else {
            $redirect_url = route('success');
        }

        // Simpan order dan alamat
        $order->save();
        $address->order_id = $order->id;
        $address->save();

        // Menambahkan item pesanan
        $order->items()->createMany($cart_items->toArray());

        // Mengurangi stok produk
        foreach ($cart_items as $item) {
            $product = $item->product;
            $new_stock = $product->in_stock - $item->quantity;

            // Memastikan stok tidak menjadi negatif
            if ($new_stock >= 0) {
                $product->in_stock = $new_stock;
                $product->save();
            } else {
                session()->flash('error', 'Stok produk ' . $product->name . ' tidak mencukupi.');
                return redirect()->route('checkout');
            }
        }

        // Menghapus item keranjang setelah checkout berhasil
        CartManagement::clearCart($user_id);

        // Mengirim email konfirmasi
        Mail::to(auth()->user())->send(new OrderPlaced($order));

        // Redirect ke halaman sukses atau pembayaran
        return redirect($redirect_url);
    }

    public function render()
    {
        $user_id = auth()->id();
        $cart_items = CartManagement::getCartItemsForUser($user_id);
        $grand_total = CartManagement::calculateGrandTotal($user_id);

        return view('livewire.checkout-page', [
            'cart_items' => $cart_items,
            'grand_total' => $grand_total,
        ]);
    }
}
