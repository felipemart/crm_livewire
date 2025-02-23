<?php

declare(strict_types = 1);

use App\Livewire\Auth\PasswordRecovery;
use App\Models\User;
use App\Notifications\PasswordRecoveryNotification;
use Illuminate\Support\Facades\Notification;
use Livewire\Livewire;

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

    Notification::assertSentTo($user, PasswordRecoveryNotification::class);
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
