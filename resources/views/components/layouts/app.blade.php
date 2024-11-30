<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <title>{{ $title ?? 'SembakoHub' }}</title>
  @vite(['resources/css/app.css', 'resources/js/app.js'])
  @livewireStyles

  <style>
    /* Pastikan body dan html mengisi seluruh tinggi layar */
    html, body {
      height: 100%;
    }

    body {
      display: flex;
      flex-direction: column;
      min-height: 100%; /* Agar body mengisi seluruh tinggi layar */
    }

    main {
      flex-grow: 1; /* Membuat main mengisi ruang yang tersisa */
    }

    footer {
      margin-top: auto; /* Menjamin footer berada di bawah halaman */
    }
  </style>
</head>

<body class="flex flex-col min-h-screen bg-slate-200 dark:bg-slate-700">
  @unless(request()->is('email/verify', 'login', 'register')) <!-- Pengecekan URL -->
    @livewire('partials.navbar') <!-- Navbar hanya ditampilkan jika bukan halaman tertentu -->
  @endunless
  
  <main class="flex-grow">
    {{ $slot }}
  </main>
  
  @unless(request()->is('email/verify', 'login', 'register')) <!-- Pengecekan URL -->
    @livewire('partials.footer') <!-- Footer hanya ditampilkan jika bukan halaman tertentu -->
  @endunless
  
  @livewireScripts
  <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <x-livewire-alert::scripts />
</body>

</html>
