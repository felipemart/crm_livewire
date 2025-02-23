<?php

declare(strict_types = 1);

namespace App\Livewire\Auth;

use App\Models\User;
use App\Notifications\PasswordRecoveryNotification;
use Illuminate\Contracts\View\View;
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

        $user = User::where('email', $this->email)->first();

        if ($user) {
            $user->notify(new PasswordRecoveryNotification());
        }

        $this->message = trans('message.Send Password Reset Link');
    }
}
