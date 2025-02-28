<?php

declare(strict_types = 1);

use App\Livewire\Auth\Login;
use App\Livewire\Auth\Logout;
use App\Livewire\Auth\PasswordRecovery;
use App\Livewire\Auth\PasswordResetProcess;
use App\Livewire\Auth\Register;
use App\Livewire\Welcome;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    Route::get('/', Welcome::class)->name('home');
});

Route::get('/login', Login::class)->name('login');
Route::get('/register', Register::class)->name('register');
Route::get('/logout', Logout::class)->name('logout');

Route::get('/password-recovery', PasswordRecovery::class)->name('password.recovery');
Route::get('password/reset', PasswordResetProcess::class)->name('password.reset');

Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();

    return redirect('/home');
})->middleware(['auth', 'signed'])->name('verification.verify');
