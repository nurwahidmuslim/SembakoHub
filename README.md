# SembakoHub - E-Commerce Platform

SembakoHub adalah platform e-commerce berbasis web yang menyediakan berbagai kebutuhan sembako dan barang sehari-hari. Platform ini memudahkan pengguna untuk membeli sembako secara online, serta memungkinkan admin untuk mengelola produk, memverifikasi pesanan, dan pembayaran dengan efisien.

## Fitur Utama

- **Toko untuk Pengguna**: Pengguna dapat melihat dan membeli berbagai produk sembako.
- **Halaman Admin**: Admin dapat mengelola produk, memverifikasi pesanan, serta memverifikasi pembayaran.
- **Proses Pembayaran yang Aman**: Pembayaran dapat dilakukan secara online dengan verifikasi yang mudah.
- **Manajemen Order**: Admin dapat melihat dan mengelola status pesanan dengan mudah.
- **Responsif**: Tampilan web yang responsif dan user-friendly, cocok diakses melalui perangkat mobile maupun desktop.

## Teknologi yang Digunakan

- **Laravel 10**: Framework PHP untuk pengembangan backend.
- **Breeze**: Untuk autentikasi pengguna dan admin.
- **Filament**: Untuk membangun halaman admin yang mudah digunakan.
- **MySQL**: Database untuk menyimpan data produk, pesanan, dan pembayaran.

## Struktur Database

1. **Tabel Products**
   - `id` - ID produk
   - `name` - Nama produk
   - `description` - Deskripsi produk
   - `price` - Harga produk
   - `quantity` - Jumlah stok produk
   - `image` - Gambar produk

2. **Tabel Orders**
   - `id` - ID pesanan
   - `user_id` - ID pengguna
   - `status` - Status pesanan (misalnya: pending, completed)
   - `total_amount` - Total harga pesanan

3. **Tabel Order_Items**
   - `id` - ID item pesanan
   - `order_id` - ID pesanan
   - `product_id` - ID produk
   - `quantity` - Jumlah produk yang dibeli
   - `price` - Harga per produk

4. **Tabel Payments**
   - `id` - ID pembayaran
   - `order_id` - ID pesanan yang dibayar
   - `amount` - Jumlah pembayaran
   - `status` - Status pembayaran (misalnya: pending, paid)

## Cara Menjalankan Project

1. Clone repository ini:
   ```bash
   git clone https://github.com/username/sembakohub.git
