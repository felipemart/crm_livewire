<?php

declare(strict_types = 1);

namespace App\Livewire\Auth;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Password;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Rule;
use Livewire\Component;

class PasswordRecovery extends Component
{
    #[Rule(['required', 'email'])]
    public string $email = '';

    public string $message = '';

    #[Layout('components.layouts.guest', ['title' => 'Recuperar Senha'])]
    public function render(): View
    {
        return view('livewire.auth.password-recovery');
    }

    public function recoverPassword(): void
    {
        $this->validate();

        Password::sendResetLink($this->only('email'));

        $this->message = 'Enviamos por e-mail o link de redefinição de senha!';
    }
}
