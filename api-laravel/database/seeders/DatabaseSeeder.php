<?php

namespace Database\Seeders;

use App\Domain\User\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'Mick Jagger',
                'email' => 'jagger@turno.com',
                'password' => Hash::make('Mick1234'),
            ],
            [
                'name' => 'John Lennon',
                'email' => 'lennon@turno.com',
                'password' => Hash::make('John1234'),
            ]
        ];

        foreach ($users as $user) {
            User::factory()->create($user);
        }
    }
}
