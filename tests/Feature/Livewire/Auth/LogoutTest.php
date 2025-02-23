<?php

declare(strict_types = 1);

use App\Livewire\Auth\Logout;
use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test(Logout::class)
        ->assertStatus(200);
})->skip();
