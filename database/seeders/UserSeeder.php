<?php

declare(strict_types = 1);

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()
            ->withPermission('admin')
            ->create([
                'name'     => 'admin User',
                'email'    => 'admin@example.com',
                'password' => 123,
            ]);

        User::factory()->count(50)->create();
    }
}
