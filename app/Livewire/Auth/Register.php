<?php

declare(strict_types = 1);

namespace App\Livewire\Auth;

use App\Models\User;
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

    public function render()
    {
        return view('livewire.auth.register');
    }

    public function submit()
    {
        $this->validate();

        $user = User::create([
            'name'     => $this->name,
            'email'    => $this->email,
            'password' => $this->password,
        ]);

        auth()->login($user);
    }
}
