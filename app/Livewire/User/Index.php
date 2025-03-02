<?php

declare(strict_types = 1);

namespace App\Livewire\User;

use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Component;

/**
 * @property-read LengthAwarePaginator<User>|User[] $users
 * @property-read  array<string, string>[] $headers
 */
class Index extends Component
{
    public ?string $search = null;

    public function render(): View
    {
        return view('livewire.user.index');
    }

    /**
     * @return LengthAwarePaginator<User>
     */
    #[Computed]
    public function users(): LengthAwarePaginator
    {
        return User::with('permissions')
            ->when(
                $this->search,
                fn (Builder $query) => $query->where(DB::raw('lower(name)'), 'like', "%" . strtolower($this->search) . "%")
                    ->orWhere('email', 'like', "%{$this->search}%")
            )
            ->paginate();
    }

    /**
     * @return array<string, string>[]
     */
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
