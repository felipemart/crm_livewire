<?php

declare(strict_types = 1);

use App\Livewire\User\Index;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

it('should be able to access the route to list users', function () {
    actingAs(User::factory()->admin()->create());
    get(route('user.index'))
        ->assertOk();
});

test('making sure that the route is protected by permission admin', function () {
    actingAs(User::factory()->create());
    get(route('user.index'))
        ->assertForbidden();
});

test('let s create a livewire component  to list all users in the page', function () {
    actingAs(User::factory()->admin()->create());
    $users = User::factory()->count(14)->create();

    $lw = Livewire::test(Index::class);
    $lw->assertSet('users', function ($users) {
        expect($users)
            ->toBeInstanceOf(LengthAwarePaginator::class)
            ->toHaveCount(15);

        return true;
    });

    foreach ($users as $user) {
        $lw->assertSee($user->name);
    }
});

test('chack the table format', function () {
    actingAs(User::factory()->admin()->create());
    Livewire::test(Index::class)
        ->assertSet('headers', [
            ['key' => 'id', 'label' => '#'],
            ['key' => 'name', 'label' => 'Nome'],
            ['key' => 'email', 'label' => 'Email'],
            ['key' => 'permissions', 'label' => 'Permissões'],
        ]);
});
