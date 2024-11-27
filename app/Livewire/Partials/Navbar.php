<?php

namespace App\Livewire\Partials;

use Livewire\Component;
use App\Helpers\CartManagement;
use Livewire\Attributes\On;

class Navbar extends Component
{
    public $total_count = 0;

    public function mount()
    {
        $user_id = auth()->id();
        if ($user_id) {
            $this->total_count = CartManagement::getCartItemsCount($user_id);
        } else {
            $this->total_count = 0;
        }
    }

    #[On('update-cart-count')]
    public function updateCartCount($total_count)
    {
        $this->total_count = $total_count;
    }

    public function render()
    {
        return view('livewire.partials.navbar');
    }
}
