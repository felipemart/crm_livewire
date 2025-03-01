<?php

declare(strict_types = 1);

namespace App\Livewire\Auth;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Login extends Component
{
    public string $email = '';

    public string $password = '';

    #[Layout('components.layouts.guest', ['title' => 'Login'])]
    public function render(): View
    {
        return view('livewire.auth.login');
    }

    public function tryLogin(): void
    {
        if (RateLimiter::tooManyAttempts($this->throtttleKey(), 5)) {
            $this->addError('rateLimiter', 'Muitas tentativas.  Por favor, tente novamente em ' . RateLimiter::availableIn($this->throtttleKey()) . ' segundos.');

            return;
        }

        if (! auth()->attempt(['email' => $this->email, 'password' => $this->password])) {
            RateLimiter::hit($this->throtttleKey());
            $this->addError('invalidCredentials', trans('auth.failed'));

            return;
        }
        auth()->user()->makeSessionPermissions();

        $this->redirect(route('home'));
    }

    /**
     * @return string
     */
    private function throtttleKey(): string
    {
        return Str::transliterate(Str::lower($this->email) . '|' . request()->ip());
    }
}
