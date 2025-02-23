<?php

declare(strict_types = 1);

use App\Livewire\Auth\PasswordRecovery;
use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\Facades\Notification;
use Livewire\Livewire;

use function Pest\Laravel\assertDatabaseCount;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\get;

test('needs to have a route to recover password', function () {
get(route('password.recovery'))
->assertSeeLivewire('auth.password-recovery')
->assertOk();
    });

it('should be able to request for a recover password', function () {
    Notification::fake();

    $user = User::factory()->create();

    Livewire::test(PasswordRecovery::class)
        ->assertDontSee(trans('message.Send Password Reset Link'))
        ->set('email', $user->email)
        ->call('recoverPassword')
        ->assertSee(trans('message.Send Password Reset Link'));

    Notification::assertSentTo($user, ResetPassword::class);
});

it('making sure the email is a real email', function ($value, $rule) {
    Livewire::test(PasswordRecovery::class)
        ->set('email', $value)
        ->call('recoverPassword')
        ->assertHasErrors(['email' => $rule]);
})->with([
    'required' => ['value' => '', 'rule' => 'required'],
    'email'    => ['value' => 'not-an-email', 'rule' => 'email'],
]);

test('needs to create a token for the user', function () {
    $user = User::factory()->create();

    Livewire::test(PasswordRecovery::class)
        ->set('email', $user->email)
        ->call('recoverPassword');

    assertDatabaseCount('password_reset_tokens', 1);
    assertDatabaseHas('password_reset_tokens', ['email' => $user->email]);
});
