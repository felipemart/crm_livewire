<?php

declare(strict_types = 1);

namespace App\Traits;

use App\Models\Permission;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

trait HasPermissons
{
    public function permissons(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class);
    }

    private function getKeySession(): string
    {
        $k = "user:" . $this->id . ".permissions";

        return $k;
    }

    public function givePermission(string $key): void
    {
        $this->permissons()->firstOrCreate(['key' => $key]);
        $this->makeSessionPermissions();
    }

    public function hasPermission(string | array $key): bool
    {
        if (is_array($key)) {
            foreach ($key as $k) {
                if ($this->hasPermission($k)) {
                    return true;
                }
            }

            return false;
        }

        $k = $this->getKeySession();

        if (! session()->has($k)) {
            $this->makeSessionPermissions();
        }
        /** @var Collection $permissons */
        $permissons = session()->get($k);

        return  $permissons->where('key', '=', $key)->isNotEmpty();
    }

    public function revokePermission(string $key): void
    {
        $this->permissons()->where('key', '=', $key)->delete();
        $this->makeSessionPermissions();
    }

    public function makeSessionPermissions()
    {
        $k = $this->getKeySession();
        session([$k => $this->permissons]);
    }
}
