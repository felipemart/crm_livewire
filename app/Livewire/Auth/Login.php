<?php

declare(strict_types = 1);

namespace App\Livewire\Auth;

use Illuminate\Contracts\View\View;
use Livewire\Component;

class Login extends Component
{
    public string $email = '';

    public string $password = '';

    public function render(): View
    {
        return view('livewire.auth.login');
    }

    public function tryLogin(): void
    {
        if (! auth()->attempt([
            'email'    => $this->email,
            'password' => $this->password,
        ])) {
            $this->addError('invalidCredentials', trans('auth.failed'));

            return;
        }
        $this->redirect(route('home'));
    }
}
