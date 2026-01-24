<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Divij',
            'email' => 'diviningu@gmail.com',
            'password' => Hash::make('abcd1234'),
            'role' => 'admin',
        ]);
        User::create([
            'name' => 'Ayush',
            'email' => 'ayush@gmail.com',
            'password' => Hash::make('abcd1234'),
            'role' => 'admin',
        ]);

        User::factory(10)->create();

        $this->call([
            CategorySeeder::class,
            TagSeeder::class,
            PostSeeder::class
        ]);
    }
}
