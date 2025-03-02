<?php

declare(strict_types = 1);

namespace App\Livewire\User;

use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Attributes\Computed;
use Livewire\Component;

/**
 * @property-read LengthAwarePaginator|User[] $users
 * @property-read  array $headers
 */
class Index extends Component
{
    public function render(): View
    {
        return view('livewire.user.index');
    }

    #[Computed]
    public function users(): LengthAwarePaginator
    {
        return User::with('permissions')->paginate();
    }

    #[Computed]
    public function headers(): array
    {
        return [
            ['key' => 'id', 'label' => '#'],
            ['key' => 'name', 'label' => 'Nome'],
            ['key' => 'email', 'label' => 'Email'],
            ['key' => 'permissions', 'label' => 'Permissões'],
        ];
    }
}
