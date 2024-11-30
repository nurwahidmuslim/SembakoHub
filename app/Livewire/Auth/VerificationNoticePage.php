<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class VerificationNoticePage extends Component
{
    public function render()
    {
        return view('livewire.auth.verification-notice-page');
    }
}

