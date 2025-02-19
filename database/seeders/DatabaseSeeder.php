<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::create([
            'uuid'     => '67372d56-5ed5-4501-8ebd-f77ffb860186',
            'email'    => 'user@test.com',
            'password' => Hash::make('my_secure_password'),
            'name'     => 'User Test'
        ]);
    }
}
