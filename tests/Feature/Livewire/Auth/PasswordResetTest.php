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

test('checking form validation', function ($field, $value, $rule) {
    Notification::fake();

    $user = User::factory()->create();

    Livewire::test(PasswordRecovery::class)
        ->set('email', $user->email)
        ->call('recoverPassword');

    Notification::assertSentTo(
        $user,
        PasswordRecoveryNotification::class,
        function (PasswordRecoveryNotification $notification) use ($user, $field, $value, $rule) {
            Livewire::test(PasswordResetProcess::class, ['token' => $notification->token, 'email' => $user->email])
                ->set($field, $value)
                ->call('resetPassword')
                ->assertHasErrors([$field => $rule]);

            return true;
        }
    );
})->with([
    'email:required'     => ['field' => 'email', 'value' => '', 'rule' => 'required'],
    'password:required'  => ['field' => 'password', 'value' => '', 'rule' => 'required'],
    'email:confirmed'    => ['field' => 'email', 'value' => 'joe@doe.com', 'rule' => 'confirmed'],
    'password:confirmed' => ['field' => 'password', 'value' => 'any-password', 'rule' => 'confirmed'],
    'email:email'        => ['field' => 'email', 'value' => 'not-an-email', 'rule' => 'email'],
]);

test('needs to show an obfuscated email', function () {
    $email           = 'emailtest@example.com';
    $obfuscatedEmail = obfucate_email($email);

    expect($obfuscatedEmail)
        ->toBe('emai*****@*******.com');

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
                ->assertSet('obfuscatedEmail', obfucate_email($user->email));

            return true;
        }
    );
});
