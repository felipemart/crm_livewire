<?php

declare(strict_types = 1);

use App\Livewire\Auth\PasswordRecovery;
use App\Models\User;
use App\Notifications\PasswordRecoveryNotification;
use Illuminate\Support\Facades\Notification;
use Livewire\Livewire;

use function Pest\Laravel\get;

test('need to receive a valid token', function () {
    Notification::fake();

    $user = User::factory()->create();

    Livewire::test(PasswordRecovery::class)
        ->set('email', $user->email)
        ->call('recoverPassword');

    Notification::assertSentTo(
        $user,
        PasswordRecoveryNotification::class,
        function (PasswordRecoveryNotification $notification) {
        get(route('password.reset') . '?token=' . $notification->token)
        ->assertSuccessful();

        get(route('password.reset') . '?token=any-token')
            ->assertRedirect(route('login'));

        return true;
        }
    );
});
