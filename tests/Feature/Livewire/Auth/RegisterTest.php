<?php

declare(strict_types = 1);

use App\Livewire\Auth\Register;
use App\Models\User;
use App\Notifications\WecomeNotification;
use Illuminate\Support\Facades\Notification;
use Livewire\Livewire;

use function Pest\Laravel\assertDatabaseCount;
use function Pest\Laravel\assertDatabaseHas;

it('should render the component', function () {
    Livewire::test(Register::class)
        ->assertOk();
});

it('should  be able to register a new user', function () {
    Livewire::test(Register::class)
        ->set('name', 'John Doe')
        ->set('email', 'john@example.com')
        ->set('email_confirmation', 'john@example.com')
        ->set('password', 'password')
        ->set('password_confirmation', 'password')
        ->call('submit')
        ->assertHasNoErrors()
        ->assertRedirectToRoute('home');

    assertDatabaseHas('users', [
        'name'  => 'John Doe',
        'email' => 'john@example.com',
    ]);

    assertDatabaseCount('users', 1);

    expect(auth()->check())
        ->and(auth()->user()->id)
        ->toBe(User::first()->id);
});

test('validation rules', function ($f) {
    if ($f->rule == 'unique') {
        User::factory()->create([$f->field => $f->value]);
    }

    $livewire = Livewire::test(Register::class)
        ->set($f->field, $f->value);

    if (property_exists($f, 'aValue')) {
        $livewire->set($f->aField, $f->aValue);
    }

    $livewire->call('submit')
        ->assertHasErrors([$f->field => $f->rule]);
})->with([
    'name::required'     => (object)['field' => 'name', 'value' => '', 'rule' => 'required'],
    'name::max:255'      => (object)['field' => 'name', 'value' => str_repeat('*', 256), 'rule' => 'max'],
    'email::required'    => (object)['field' => 'email', 'value' => '', 'rule' => 'required'],
    'email::email'       => (object)['field' => 'email', 'value' => 'not-an-email', 'rule' => 'email'],
    'email::max:255'     => (object)['field' => 'email', 'value' => str_repeat('*' . '@doe.com', 256), 'rule' => 'max'],
    'email::confirmed'   => (object)['field' => 'email', 'value' => 'joe@doe.com', 'rule' => 'confirmed'],
    'email::unique'      => (object)['field' => 'email', 'value' => 'joe@doe.com', 'rule' => 'unique', 'aField' => 'email_confirmation', 'aValue' => 'joe@doe.com'],
    'password::required' => (object)['field' => 'password', 'value' => '', 'rule' => 'required'],
]);

it('should send a notification welcoming the new user', function () {
    Notification::fake();

    Livewire::test(Register::class)
        ->set('name', 'John Doe')
        ->set('email', 'john@example.com')
        ->set('email_confirmation', 'john@example.com')
        ->set('password', 'password')
        ->set('password_confirmation', 'password')
        ->call('submit')
        ->assertHasNoErrors()
        ->assertRedirectToRoute('home');

    Notification::assertSentTo(User::first(), WecomeNotification::class);
});
