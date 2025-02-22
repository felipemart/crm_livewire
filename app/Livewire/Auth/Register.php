<?php

declare(strict_types = 1);

namespace App\Livewire\Auth;

use App\Models\User;
use App\Notifications\WecomeNotification;
use Illuminate\View\View;
use Livewire\Attributes\Rule;
use Livewire\Component;

class Register extends Component
{
    #[Rule(['required', 'max:255'])]
    public string $name = '';

    #[Rule(['required', 'email', 'max:255', 'confirmed', 'unique:users,email'])]
    public string $email = '';

    public string $email_confirmation = '';

    #[Rule(['required'])]
    public string $password = '';

    public string $password_confirmation = '';

    public function render(): View
    {
        return view('livewire.auth.register')
            ->layout('components.layouts.guest', ['title' => 'Register']);
    }

    public function submit(): void
    {
        auth()->logout();
        session()->invalidate();
        session()->regenerateToken();
        $this->validate();

        $user = User::create([
            'name'     => $this->name,
            'email'    => $this->email,
            'password' => $this->password,
        ]);

        auth()->login($user);

        $user->notify(new WecomeNotification());

        $this->redirect(route('home'));
    }
}
