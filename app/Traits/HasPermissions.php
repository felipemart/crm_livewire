<?php

declare(strict_types = 1);

namespace App\Traits;

use App\Models\Permission;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

trait HasPermissions
{
    /** @return BelongsToMany<Permission, $this> */
    public function permissions(): BelongsToMany
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
        $this->permissions()->firstOrCreate(['key' => $key]);
        $this->makeSessionPermissions();
    }

    /**
     * @param string|array<string> $key
     * @return bool
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
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
        /** @var Collection<int, Permission> */
        $permissons = session()->get($k);

        return  $permissons->where('key', '=', $key)->isNotEmpty();
    }

    /**
     * @param string $key
     * @return void
     */
    public function revokePermission(string $key): void
    {
        $this->permissons()->where('key', '=', $key)->delete();
        $this->makeSessionPermissions();
    }

    public function makeSessionPermissions(): void
    {
        $k = $this->getKeySession();
        session([$k => $this->permissions]);
    }
}
