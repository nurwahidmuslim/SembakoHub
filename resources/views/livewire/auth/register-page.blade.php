<div class="w-full max-w-[85rem] py-10 px-4 sm:px-6 lg:px-8 mx-auto">
  <div class="flex h-full items-center">
    <main class="w-full max-w-md mx-auto p-6">
      <div class="bg-white border border-gray-200 rounded-xl shadow-lg dark:bg-gray-800 dark:border-gray-700">
        <div class="p-6 sm:p-8">
          <div class="text-center">
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Daftar</h1>
            <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
              Sudah memiliki akun? 
              <a wire:navigate class="text-blue-600 underline hover:text-blue-500 font-medium dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600" href="/login">
                Masuk di sini
              </a>
            </p>
          </div>

          <hr class="my-5 border-slate-300">

          <!-- Form -->
          <form wire:submit.prevent="save">
            <div class="grid gap-y-4">
              <!-- Nama -->
              <div>
                <label for="name" class="block text-sm font-medium mb-2 text-gray-700 dark:text-white">Nama Lengkap</label>
                <div class="relative">
                  <input type="text" id="name" wire:model="name" class="py-3 px-4 block w-full border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none dark:bg-gray-900 dark:border-gray-700 dark:text-gray-300" aria-describedby="name-error">
                  @error('name')
                  <p class="text-xs text-red-600 mt-2" id="name-error">{{ $message }}</p>
                  @enderror
                </div>
              </div>

              <!-- Email -->
              <div>
                <label for="email" class="block text-sm font-medium mb-2 text-gray-700 dark:text-white">Alamat Email</label>
                <div class="relative">
                  <input type="email" id="email" wire:model="email" class="py-3 px-4 block w-full border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none dark:bg-gray-900 dark:border-gray-700 dark:text-gray-300" aria-describedby="email-error">
                  @error('email')
                  <p class="text-xs text-red-600 mt-2" id="email-error">{{ $message }}</p>
                  @enderror
                </div>
              </div>

              <!-- Kata Sandi -->
              <div>
                <label for="password" class="block text-sm font-medium mb-2 text-gray-700 dark:text-white">Kata Sandi</label>
                <div class="relative">
                  <input type="password" id="password" wire:model="password" class="py-3 px-4 block w-full border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none dark:bg-gray-900 dark:border-gray-700 dark:text-gray-300" aria-describedby="password-error">
                  @error('password')
                  <p class="text-xs text-red-600 mt-2" id="password-error">{{ $message }}</p>
                  @enderror
                </div>
              </div>

              <!-- Tombol Daftar -->
              <button type="submit" class="w-full py-3 px-4 text-sm font-semibold rounded-lg bg-blue-600 text-white hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                Daftar
              </button>
            </div>
          </form>
          <!-- End Form -->
        </div>
      </div>
    </main>
  </div>
</div>
