<?php

declare(strict_types = 1);

namespace App\Livewire\Auth;

use App\Models\User;
use App\Notifications\WecomeNotification;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
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

    #[Layout('components.layouts.guest', ['title' => 'Criar Conta'])]
    public function render(): View
    {
        return view('livewire.auth.register');
    }

    public function submit(): void
    {
        //TODO: Validar se o e-mail ja foi cadastrado
        //TODO: remover o login automatico?
        //TODO: refazer o email de boas vindas
        //TODO: enviar email de verificação

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
