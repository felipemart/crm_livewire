<?php

declare(strict_types = 1);

namespace App\Livewire\Admin;

use Illuminate\Contracts\View\View;
use Livewire\Component;

class Dashboard extends Component
{
    public function mount(): void
    {
        if (! auth()->user()->hasPermission('admin')) {
            abort(403);
        }
    }

    public function render(): View
    {
        return view('livewire.admin.dashboard');
    }
}
