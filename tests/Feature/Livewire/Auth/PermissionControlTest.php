<?php

declare(strict_types = 1);

use App\Livewire\Auth\Login;
use App\Models\Permission;
use App\Models\User;
use Database\Seeders\PermissionSeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Livewire\Livewire;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertDatabaseMissing;
use function Pest\Laravel\seed;

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

test('seed with an admin user', function () {
    seed([PermissionSeeder::class, UserSeeder::class]);

    assertDatabaseHas('permissions', [
        'key' => 'admin',
    ]);
    assertDatabaseHas('permission_user', [
        'user_id'       => User::first()->id,
        'permission_id' => Permission::where('key', '=', 'admin')->first()?->id,
    ]);
});

it('should block access if user does not have permission', function () {
    $user = User::factory()->create();
    actingAs($user)
        ->get(route('admin.dashboard'))
        ->assertForbidden();
});

test("let's make sure that we are using cache to store permissions", function () {
    $user = User::factory()->create();
    $user->givePermission('admin');
    $sessionKey = "user:" . $user->id . ".permissions";

    expect(Session::has($sessionKey))->toBeTrue('we should have a session key')
        ->and(Session::get($sessionKey))->toBe($user->permissons, 'checking the session permissions');
});

test("Login create a permisson session", function () {
    $user = User::factory()
        ->withPermission('admin')
        ->create([
            'email'    => 'john@example.com',
            'password' => 'password',
        ]);

    $sessionKey = "user:" . $user->id . ".permissions";
    Session::forget($sessionKey);

    Livewire::test(Login::class)
        ->set('email', 'john@example.com')
        ->set('password', 'password')
        ->call('tryLogin')
        ->assertHasNoErrors()
        ->assertRedirect(route('home'));

    expect(Session::has($sessionKey))->toBeTrue('we should have a session key')
        ->and(Session::get($sessionKey)->toArray())->toBe($user->permissons->toArray(), 'checking the session permissions');
});

test('lets make sure that we are using cache to store permissions', function () {
    $user = User::factory()->create();
    $user->givePermission('admin');

    DB::listen(fn () => throw new Exception('we got a hit'));

    $user->hasPermission('admin');
    expect(true)->toBeTrue();
});

test('revoke a permission user ', function () {
    $user = User::factory()->create();
    $user->givePermission('admin');

    assertDatabaseHas('permissions', [
        'key' => 'admin',
    ]);
    assertDatabaseHas('permission_user', [
        'user_id'       => $user->id,
        'permission_id' => Permission::where('key', '=', 'admin')->first()?->id,
    ]);

    $user->revokePermission('admin');
    assertDatabaseMissing('permission_user', [
        'user_id'       => $user->id,
        'permission_id' => Permission::where('key', '=', 'admin')->first()?->id,
    ]);

    $sessionKey = "user:" . $user->id . ".permissions";
    expect(Session::has($sessionKey))->toBeTrue('we should have a session key')
        ->and(Session::get($sessionKey)->toArray())->toBe($user->permissons->toArray(), 'checking the session permissions');
});
