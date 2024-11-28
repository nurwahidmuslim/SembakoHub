<div class="w-full max-w-[85rem] py-10 px-4 sm:px-6 lg:px-8 mx-auto">
    <section class="overflow-hidden bg-white py-11 font-poppins dark:bg-gray-800" style="padding-top: 4rem;"> <!-- Adjust padding -->
      <div class="max-w-6xl px-4 py-4 mx-auto lg:py-8 md:px-6">
        <div class="flex flex-wrap -mx-4">
          <!-- Image Section -->
          <div class="w-full mb-8 md:w-1/2 md:mb-0" x-data="{ mainImage: '{{asset('storage/' . $product->images[0])}}' }">
            <div class="sticky top-[4rem] z-40 overflow-hidden"> <!-- Adjust top spacing -->
              <div class="relative mb-6 lg:mb-10 lg:h-2/4">
                <img x-bind:src="mainImage" alt="" class="object-cover w-full lg:h-full">
              </div>

              <div class="flex-wrap hidden md:flex">
                @foreach ($product->images as $image)
                  <div class="w-1/2 p-2 sm:w-1/4" x-on:click="mainImage='{{asset('storage/' . $image)}}'">
                    <img src="{{asset('storage/' . $image)}}" alt="{{$product->name}}" class="object-cover w-full lg:h-20 cursor-pointer hover:border hover:border-blue-500">
                  </div>
                @endforeach
              </div>
              <div class="px-6 pb-6 mt-6 border-t border-gray-300 dark:border-gray-400">
                <div class="flex flex-wrap items-center mt-6">
                  <span class="mr-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="w-4 h-4 text-gray-700 dark:text-gray-400 bi bi-truck" viewBox="0 0 16 16">
                      <path d="..."></path>
                    </svg>
                  </span>
                  <h2 class="text-lg font-bold text-gray-700 dark:text-gray-400">Gratis Ongkir</h2>
                </div>
              </div>
            </div>
          </div>

          <!-- Product Details Section -->
          <div class="w-full px-4 md:w-1/2">
            <div class="lg:pl-20">
              <div class="mb-8 [&>ul]:list-disc [&>ul]:ml-4">
                <h2 class="max-w-xl mb-6 text-2xl font-bold dark:text-gray-400 md:text-4xl">
                  {{$product->name}}</h2>
                <p class="inline-block mb-6 text-4xl font-bold text-gray-700 dark:text-gray-400">
                  <span>IDR {{ number_format($product->price, 0, '', '') }}</span>
                </p>
                <p class="max-w-md text-gray-700 dark:text-gray-400">
                  {!!Str::markdown($product->description)!!}
                </p>
              </div>
              <div class="w-32 mb-8">
                <label for="" class="w-full pb-1 text-xl font-semibold text-gray-700 border-b border-blue-300 dark:border-gray-600 dark:text-gray-400">Jumlah</label>
                <div class="relative flex flex-row w-full h-10 mt-6 bg-transparent rounded-lg">
                  <button wire:click="decreaseQty" class="w-20 h-full text-gray-600 bg-gray-300 rounded-l outline-none cursor-pointer dark:hover:bg-gray-700 dark:text-gray-400 hover:text-gray-700 dark:bg-gray-900 hover:bg-gray-400">
                    <span class="m-auto text-2xl font-thin">-</span>
                  </button>
                  <input type="number" wire:model="quantity" readonly class="flex items-center w-full font-semibold text-center text-gray-700 placeholder-gray-700 bg-gray-300 outline-none dark:text-gray-400 dark:placeholder-gray-400 dark:bg-gray-900 focus:outline-none text-md hover:text-black" placeholder="1">
                  <button wire:click="increaseQty" class="w-20 h-full text-gray-600 bg-gray-300 rounded-r outline-none cursor-pointer dark:hover:bg-gray-700 dark:text-gray-400 dark:bg-gray-900 hover:text-gray-700 hover:bg-gray-400">
                    <span class="m-auto text-2xl font-thin">+</span>
                  </button>
                </div>
              </div>
              <div class="flex flex-wrap items-center gap-4">
                <button wire:click='addToCart({{ $product->id }})' 
                        class="inline-flex items-center p-4 bg-blue-500 rounded-md text-gray-50 hover:bg-blue-600 dark:bg-blue-500 dark:hover:bg-blue-700 dark:text-gray-200">
                  <span wire:loading.remove wire:target="addToCart({{ $product->id }})">Tambahkan ke Keranjang</span>
                  <span wire:loading wire:target="addToCart({{ $product->id }})">Menambahkan...</span>
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>