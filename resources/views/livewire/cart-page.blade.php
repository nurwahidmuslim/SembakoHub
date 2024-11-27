<div class="w-full max-w-[85rem] py-10 px-4 sm:px-6 lg:px-8 mx-auto">
  <div class="container mx-auto px-4">
    <h1 class="text-2xl font-semibold mb-4">Keranjang Belanja</h1>
    <div class="flex flex-col md:flex-row gap-4">
      <div class="md:w-3/4">
        <div class="bg-white overflow-x-auto rounded-lg shadow-md p-6 mb-4">
          <table class="w-full">
            <thead>
                <tr>
                    <th class="text-center font-semibold">Produk</th>
                    <th class="text-center font-semibold">Harga</th>
                    <th class="text-center font-semibold">Jumlah</th>
                    <th class="text-center font-semibold">Total</th>
                    <th class="text-center font-semibold">Hapus</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($cart_items as $item)
                    <tr wire:key='{{$item['product_id']}}'>
                        <td class="py-4 text-center">
                            <div class="flex items-center justify-center">
                                <img class="h-16 w-16 mr-4" src="{{asset('storage/' . $item->product->images[0])}}" alt="{{$item->product->name}}">
                                <span class="font-semibold">{{$item->product->name}}</span>
                            </div>
                        </td>
                        <td class="py-4 text-center">
                            IDR {{ number_format($item['unit_amount'], 0, '', '') }}
                        </td>
                        <td class="py-4 text-center">
                            <div class="flex items-center justify-center">
                                <button wire:click="decreaseQty({{$item['product_id']}})" class="border rounded-md py-2 px-4 mr-2">-</button>
                                <span class="text-center w-8">{{$item['quantity']}}</span>
                                <button wire:click="increaseQty({{$item['product_id']}})" class="border rounded-md py-2 px-4 ml-2">+</button>
                            </div>
                        </td>
                        <td class="py-4 text-center">
                            IDR {{ number_format($item['total_amount'], 0, '', '') }}
                        </td>
                        <td class="text-center">
                            <button wire:click="removeItem({{$item['product_id']}})" class="bg-slate-300 border-2 border-slate-400 rounded-lg px-3 py-1 hover:bg-red-500 hover:text-white hover:border-red-700">
                                <span wire:loading.remove wire:target="removeItem({{$item['product_id']}})">Hapus</span>
                                <span wire:loading wire:target="removeItem({{$item['product_id']}})">Menghapus...</span>
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-4 text-4x1 font-semibold text-slate-500">Keranjang Kosong</td>
                    </tr>
                @endforelse
            </tbody>
          </table>
        </div>
      </div>
      <div class="md:w-1/4">
        <div class="bg-white rounded-lg shadow-md p-6">
          <h2 class="text-lg font-semibold mb-4 text-gray-900 dark:text-white">Ringkasan</h2>
          <div class="space-y-4">
            @foreach ($cart_items as $item)
              <div class="flex justify-between items-center border-b border-gray-200 dark:border-gray-700 last:border-none">
                <div class="flex flex-col">
                  <span class="text-sm font-medium text-gray-900 truncate dark:text-white">
                    {{ $item->product->name }}
                  </span>
                  <span class="text-sm text-gray-500 truncate dark:text-gray-400">
                    Jumlah: {{ $item['quantity'] }}
                  </span>
                </div>
                <span class="text-sm font-medium text-gray-900 dark:text-white">
                  IDR {{ number_format($item['total_amount'], 0, '', '.') }}
                </span>
              </div>
            @endforeach
          </div>
          <div class="flex justify-between p-2">
            <span class="text-base font-semibold text-gray-900 dark:text-white">Total</span>
            <span class="text-base font-semibold text-gray-900 dark:text-white">
              IDR {{ number_format($grand_total, 0, '', '.') }}
            </span>
          </div>
          @if ($cart_items)
            <a href="/checkout" class="bg-blue-500 block text-center text-white py-2 px-4 rounded-lg mt-4 w-full">
              Checkout
            </a>
          @endif
        </div>
      </div>
    </div>
  </div>
</div>
