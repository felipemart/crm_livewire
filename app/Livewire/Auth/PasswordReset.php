<?php

declare(strict_types = 1);

namespace App\Livewire\Auth;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Layout;
use Livewire\Component;

class PasswordReset extends Component
{
    public string $token = '';

    public string $email = '';

    #[Layout('components.layouts.guest', ['title' => 'Recuperar Senha'])]
    public function render(): View
    {
        return view('livewire.auth.password-reset');
    }

    public function mount(): void
    {
        $this->token = request('token', $this->token);

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
}
