<div class="w-full max-w-[85rem] py-10 px-4 sm:px-6 lg:px-8 mx-auto">
  <div class="flex h-full items-center">
    <main class="w-full max-w-md mx-auto p-6">
      <div class="mt-7 bg-white border border-gray-200 rounded-xl shadow-sm dark:bg-gray-800 dark:border-gray-700">
        <div class="p-4 sm:p-7">
          <div class="text-center">
            <h1 class="block text-2xl font-bold text-gray-800 dark:text-white">Reset Kata Sandi</h1>
          </div>

          <div class="mt-5">
            <!-- Form -->
            <form wire:submit.prevent="save">

              @if (session('error'))
              <div class="text-sm text-red-600 rounded-lg p-4 mb-4" role="alert">
                {{ session('error') }}
              </div>
              @endif

              <div class="grid gap-y-4">
                <!-- Kata Sandi Baru -->
                <div>
                  <label for="password" class="block text-sm mb-2 dark:text-white">Kata Sandi Baru</label>
                  <div class="relative">
                    <input type="password" id="password" wire:model="password" class="py-3 px-4 block w-full border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none dark:bg-slate-900 dark:border-gray-700 dark:text-gray-400" aria-describedby="password-error">
                    @error('password')
                    <p class="text-xs text-red-600 mt-2" id="password-error">{{$message}}</p>
                    @enderror
                  </div>
                </div>

                <!-- Konfirmasi Kata Sandi -->
                <div>
                  <label for="password_confirmation" class="block text-sm mb-2 dark:text-white">Konfirmasi Kata Sandi</label>
                  <div class="relative">
                    <input type="password" id="password_confirmation" wire:model="password_confirmation" class="py-3 px-4 block w-full border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none dark:bg-slate-900 dark:border-gray-700 dark:text-gray-400" aria-describedby="password_confirmation-error">
                    @error('password_confirmation')
                    <p class="text-xs text-red-600 mt-2" id="password_confirmation-error">{{$message}}</p>
                    @enderror
                  </div>
                </div>

                <!-- Tombol Simpan -->
                <button type="submit" class="mt-5 w-full py-3 px-4 inline-flex justify-center items-center gap-x-2 text-sm font-semibold rounded-lg bg-blue-600 text-white hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                  Simpan Kata Sandi
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
