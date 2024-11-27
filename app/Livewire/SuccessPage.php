<?php

namespace App\Livewire;

use App\Models\Order;
use Livewire\Attributes\Title;
use Livewire\Component;
use GuzzleHttp\Client;

#[Title('Success')]
class SuccessPage extends Component
{
    public function render()
    {
        // Mengambil order terbaru berdasarkan order_id yang disimpan di database
        $latest_order = Order::with('address')
            ->where('user_id', auth()->user()->id)
            ->latest()
            ->first();

        // Mengecek status pembayaran transaksi di Midtrans menggunakan snap_token
        if ($latest_order && $latest_order->snap_token) {
            try {
                // Inisialisasi Guzzle client
                $client = new Client();

                // URL untuk memeriksa status transaksi di Midtrans
                $url = 'https://api.sandbox.midtrans.com/v2/' . $latest_order->order_id . '/status';

                // Melakukan request untuk mendapatkan status transaksi
                $response = $client->request('GET', $url, [
                    'headers' => [
                        'Authorization' => 'Basic ' . base64_encode(env('MIDTRANS_SERVER_KEY') . ':'), // Gunakan API key
                        'Content-Type' => 'application/json',
                    ]
                ]);

                // Mendekode respons JSON dari API Midtrans
                $responseBody = json_decode($response->getBody(), true);

                // Memeriksa status transaksi
                if (isset($responseBody['transaction_status'])) {
                    $transactionStatus = $responseBody['transaction_status'];

                    // Update status pembayaran berdasarkan transaction_status dari Midtrans
                    if (in_array($transactionStatus, ['settlement', 'capture'])) {
                        $latest_order->payment_status = 'paid';
                    } elseif (in_array($transactionStatus, ['pending'])) {
                        $latest_order->payment_status = 'pending';
                    } elseif (in_array($transactionStatus, ['deny', 'cancel', 'expire'])) {
                        $latest_order->payment_status = 'failed';
                    }

                    // Menyimpan perubahan status
                    $latest_order->save();
                }
            } catch (\Exception $e) {
                // Menangani kesalahan saat melakukan pengecekan status transaksi
                \Log::error('Error checking Midtrans payment status: ' . $e->getMessage());
            }
        }

        // Menampilkan halaman success dengan data pesanan terbaru
        return view('livewire.success-page', [
            'order' => $latest_order,
        ]);
    }
}
