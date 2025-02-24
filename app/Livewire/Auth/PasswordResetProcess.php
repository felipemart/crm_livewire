<?php

declare(strict_types = 1);

namespace App\Livewire\Auth;

use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Rule;
use Livewire\Component;

class PasswordResetProcess extends Component
{
    public string $token = '';

    #[Rule(['required', 'email', 'confirmed'])]
    public string $email = '';

    public string $email_confirmation = '';

    #[Rule(['required', 'confirmed'])]
    public string $password = '';

    public string $password_confirmation = '';

    #[Layout('components.layouts.guest', ['title' => 'Recuperar Senha'])]
    public function render(): View
    {
        return view('livewire.auth.password-reset');
    }

    public function mount(?string $token = null, ?string $email = null): void
    {
        $this->token = request('token', $token);
        $this->email = request('email', $email);

        if ($this->tokenInvalido()) {
            session()->flash('status', 'Token inválido.');
            $this->redirectRoute('login');
        }
    }

    private function tokenInvalido(): bool
    {
        $tokens = DB::table('password_reset_tokens')->get(['token']);

        foreach ($tokens as $t) {
            if (Hash::check($this->token, $t->token)) {
                return false;
            }
        }

        return true;
    }

    public function resetPassword(): void
    {
        $this->validate();

        Password::reset($this->only('email', 'password', 'password_confirmation', 'token'), function ($user, $password) {
            $user->password = $password;
            $user->setRememberToken(Str::random(60));
            $user->save();

            event(new PasswordReset($user));
        });
        $this->redirectRoute('home');
    }
}
