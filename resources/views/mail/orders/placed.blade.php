<x-mail::message>
# Pesanan Berhasil Dibuat!

Terima kasih atas pesanan Anda.  
Nomor pesanan Anda adalah: **{{ $order->id }}**.

<x-mail::button :url="$url">
Lihat Pesanan
</x-mail::button>

Terima kasih,  
{{ config('app.name') }}
</x-mail::message>
