<?php

declare(strict_types = 1);

use App\Models\Permission;
use App\Models\User;
use Database\Seeders\PermissionSeeder;

use function Pest\Laravel\assertDatabaseHas;

it('should br able to give an useer a permission', function () {
    /** @var $user */
    $user = User::factory()->create();

    $user->givePermission('admin');

    expect($user)
        ->hasPermission('admin')
        ->toBeTrue();

    assertDatabaseHas('permissions', [
        'key' => 'admin',
    ]);

    assertDatabaseHas('permission_user', [
        'user_id'       => $user->id,
        'permission_id' => Permission::where('key', '=', 'admin')->first()->id,
    ]);
});

test('permission has a seeder', function () {
    $this->seed(PermissionSeeder::class);
    assertDatabaseHas('permissions', [
        'key' => 'admin',
    ]);
});
