<?php

declare(strict_types = 1);

namespace App\Livewire\Auth;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Password;
use Livewire\Attributes\Rule;
use Livewire\Component;

class PasswordRecovery extends Component
{
    #[Rule(['required', 'email'])]
    public string $email = '';

    public string $message = '';

    public function render(): View
    {
        return view('livewire.auth.password-recovery')
            ->layout('components.layouts.guest', ['title' => 'Login']);
    }

    public function recoverPassword(): void
    {
        $this->validate();

        Password::sendResetLink($this->only('email'));

        $this->message = trans('message.Send Password Reset Link');
    }
}
