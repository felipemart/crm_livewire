<?php

declare(strict_types = 1);

namespace App\Livewire\Auth;

use App\Models\User;
use App\Notifications\PasswordRecoveryNotification;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class PasswordRecovery extends Component
{
    public string $email = '';

    public string $message = '';

    public function render(): View
    {
        return view('livewire.auth.password-recovery');
    }

    public function recoverPassword(): void
    {
        $user = User::where('email', $this->email)->first();

        if ($user) {
            $user->notify(new PasswordRecoveryNotification());
        }

        $this->message = trans('message.Send Password Reset Link');
    }
}
