<?php

declare(strict_types = 1);

namespace App\Livewire\Admin;

use Livewire\Component;

class Dashboard extends Component
{
    public function mount()
    {
        if (! auth()->user()->hasPermission('admin')) {
            abort(403);
        }
    }

    public function render()
    {
        return view('livewire.admin.dashboard');
    }
}
