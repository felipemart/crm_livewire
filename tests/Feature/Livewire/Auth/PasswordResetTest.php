<?php

declare(strict_types = 1);

use App\Livewire\Auth\PasswordRecovery;
use App\Livewire\Auth\PasswordResetProcess;
use App\Models\User;
use App\Notifications\PasswordRecoveryNotification;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Livewire\Livewire;

use function Pest\Laravel\get;
use function PHPUnit\Framework\assertTrue;

test('need to receive a valid token', function () {
    Notification::fake();

    $user = User::factory()->create();

    Livewire::test(PasswordRecovery::class)
        ->set('email', $user->email)
        ->call('recoverPassword');

    Notification::assertSentTo(
        $user,
        PasswordRecoveryNotification::class,
        function (PasswordRecoveryNotification $notification) use ($user) {
        get(route('password.reset') . '?token=' . $notification->token . '&email=' . $user->email)
        ->assertSuccessful();

        get(route('password.reset') . '?token=any-token&email=any-email')
            ->assertRedirect(route('login'));

        return true;
        }
    );
});

test('test if possible to reset password', function () {
    Notification::fake();

    $user = User::factory()->create();

    Livewire::test(PasswordRecovery::class)
        ->set('email', $user->email)
        ->call('recoverPassword');

    Notification::assertSentTo(
        $user,
        PasswordRecoveryNotification::class,
        function (PasswordRecoveryNotification $notification) use ($user) {
            Livewire::test(PasswordResetProcess::class, ['token' => $notification->token, 'email' => $user->email])
                ->set('email_confirmation', $user->email)
                ->set('password', 'new-password')
                ->set('password_confirmation', 'new-password')
                ->call('resetPassword')
                ->assertHasNoErrors()
                ->assertRedirectToRoute('home');

            $user->refresh();

            assertTrue(Hash::check('new-password', $user->password));

            return true;
        }
    );
});
