<?php

declare(strict_types = 1);

namespace App\Livewire\Auth;

use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Logout extends Component
{
    #[Layout('components.layouts.guest', ['title' => 'Logout'])]
    public function render(): View
    {
        return view('livewire.auth.logout')
            ->layout('components.layouts.guest', ['title' => 'Logout']);
    }

    public function logout(): void
    {
        auth()->logout();
        session()->invalidate();
        session()->regenerateToken();
        $this->redirect(route('login'));
    }
}
