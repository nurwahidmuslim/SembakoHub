<div class="w-full max-w-[85rem] py-10 px-4 sm:px-6 lg:px-8 mx-auto">
  <div class="flex h-full items-center">
    <main class="w-full max-w-md mx-auto p-6">
      <div class="bg-white border border-gray-200 rounded-xl shadow-lg dark:bg-gray-800 dark:border-gray-700">
        <div class="p-6 sm:p-8">
        <div class="flex justify-center mb-6">
            <img src="storage/logo.png" alt="Logo" class="w-20 h-20 object-cover">
          </div>
          <div class="text-center">
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Masuk</h1>
            <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
              Belum punya akun? 
              <a wire:navigate class="text-blue-600 underline hover:text-blue-500 font-medium dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600" href="/register">
                Daftar di sini
              </a>
            </p>
          </div>

          <hr class="my-5 border-gray-300">

          <!-- Flash Message -->
          @if (session('success'))
            <div class="bg-green-500 text-white p-4 rounded mb-4">
                {{ session('success') }}
            </div>
          @endif

          @if (session('error'))
            <div class="text-sm text-red-600 bg-red-100 border border-red-400 rounded-lg p-4 mb-4" role="alert">
                {{ session('error') }}
            </div>
          @endif

          <!-- Form -->
          <form wire:submit.prevent='save'>
            <div class="grid gap-y-4">
              <!-- Form Group -->
              <div>
                <label for="email" class="block text-sm font-medium mb-2 text-gray-700 dark:text-white">Alamat Email</label>
                <div class="relative">
                  <input type="email" id="email" wire:model="email" class="py-3 px-4 w-full border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none dark:bg-gray-900 dark:border-gray-700 dark:text-gray-300" aria-describedby="email-error">
                  @error('email')
                  <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                    <svg class="h-5 w-5 text-red-500" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 16 16">
                      <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM8 4a.905.905 0 0 0-.9.995l.35 3.507a.552.552 0 0 0 1.1 0l.35-3.507A.905.905 0 0 0 8 4zm.002 6a1 1 0 1 0 0 2 1 1 0 0 0 0-2z" />
                    </svg>
                  </div>
                  @enderror
                </div>
                @error('email')
                <p class="text-xs text-red-600 mt-2" id="email-error">{{ $message }}</p>
                @enderror
              </div>
              <!-- End Form Group -->

              <!-- Form Group -->
              <div>
                <div class="flex justify-between items-center">
                  <label for="password" class="block text-sm font-medium mb-2 text-gray-700 dark:text-white">Kata Sandi</label>
                  <a wire:navigate class="text-sm text-blue-600 underline hover:text-blue-500 font-medium dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600" href="/forgot">Lupa kata sandi?</a>
                </div>
                <div class="relative">
                  <input type="password" id="password" wire:model="password" class="py-3 px-4 w-full border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none dark:bg-gray-900 dark:border-gray-700 dark:text-gray-300" aria-describedby="password-error">
                  @error('password')
                  <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                    <svg class="h-5 w-5 text-red-500" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 16 16">
                      <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM8 4a.905.905 0 0 0-.9.995l.35 3.507a.552.552 0 0 0 1.1 0l.35-3.507A.905.905 0 0 0 8 4zm.002 6a1 1 0 1 0 0 2 1 1 0 0 0 0-2z" />
                    </svg>
                  </div>
                  @enderror
                </div>
                @error('password')
                <p class="text-xs text-red-600 mt-2" id="password-error">{{ $message }}</p>
                @enderror
              </div>
              <!-- End Form Group -->

              <button type="submit" class="w-full py-3 px-4 text-sm font-semibold rounded-lg bg-blue-600 text-white hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                Masuk
              </button>
            </div>
          </form>
          <!-- End Form -->
        </div>
      </div>
    </main>
  </div>
</div>
