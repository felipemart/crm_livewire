<?php

declare(strict_types = 1);

namespace App\Livewire\User;

use App\Models\Permission;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithPagination;

/**
 * @property-read LengthAwarePaginator<User>|User[] $users
 * @property-read  array<string, string>[] $headers
 */
class Index extends Component
{
    use WithPagination;

    public int $perPage = 10;

    public bool $filtros = false;

    public array $sortBy = ['column' => 'id', 'direction' => 'asc'];

    public ?string $search = null;

    public ?string $nome = null;

    public ?string $email = null;

    public Collection $permissionsToSearch;

    public ?array $searchPermissions = [];

    public bool $search_trash = false;

    public function mount(): void
    {
        $this->filterPermissions();
    }

    public function updatedPerPage($value): void
    {
        $this->resetPage();
    }

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
            ->paginate($this->perPage);
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

    public function filterPermissions(?string $value = null): void
    {
        $this->permissionsToSearch = Permission::query()
            ->when($value, fn (Builder $q) => $q->where('key', 'like', "%$value%"))
            ->orderBy('key')
            ->get();
    }
}
