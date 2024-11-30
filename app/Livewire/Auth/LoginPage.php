<?php

namespace App\Livewire\Auth;

use Livewire\Attributes\Title;
use Livewire\Component;

#[Title("Login - SembakoHub")]
class LoginPage extends Component
{
    public $email;
    public $password;

    public function save()
    {
        $this->validate([
            'email' => 'required|email|max:255|exists:users,email',
            'password' => 'required|min:6|max:255',
        ]);

        // Attempt to log in
        if (!auth()->attempt(['email' => $this->email, 'password' => $this->password])) {
            session()->flash('error', 'Invalid Email password');
            return;
        }

        // Cek apakah email sudah diverifikasi
        if (auth()->user()->email_verified_at === null) {
            // Kirim ulang email verifikasi
            auth()->user()->sendEmailVerificationNotification();

            // Redirect ke halaman verifikasi
            session()->flash('error', 'Silakan verifikasi email Anda');
            return redirect()->route('verification.notice');
        }

        // Redirect ke halaman yang dituju setelah login berhasil
        return redirect()->intended();
    }

    public function render()
    {
        return view('livewire.auth.login-page');
    }
}
