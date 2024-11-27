<div class="w-full max-w-[85rem] py-10 px-4 sm:px-6 lg:px-8 mx-auto">
  <div class="flex h-full items-center">
    <main class="w-full max-w-md mx-auto p-6">
      <div class="mt-7 bg-white border border-gray-200 rounded-xl shadow-lg dark:bg-gray-800 dark:border-gray-700">
        <div class="p-6 sm:p-8">
          <div class="text-center">
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Lupa Kata Sandi?</h1>
            <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
              Ingat kata sandi Anda? 
              <a wire:navigate class="text-blue-600 underline hover:text-blue-500 font-medium dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600" href="/login">
                Masuk di sini
              </a>
            </p>
          </div>

          <div class="mt-6">
            <!-- Form -->
            <form wire:submit.prevent="save">

              @if (session('success'))
                <div class="text-sm text-green-600 bg-green-100 border border-green-400 rounded-lg p-4 mb-4" role="alert">
                  {{ session('success') }}
                </div>
              @endif

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

                <button type="submit" class="w-full py-3 px-4 text-sm font-semibold rounded-lg bg-blue-600 text-white hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                  Reset Kata Sandi
                </button>
              </div>
            </form>
            <!-- End Form -->
          </div>
        </div>
      </div>
    </main>
  </div>
</div>
