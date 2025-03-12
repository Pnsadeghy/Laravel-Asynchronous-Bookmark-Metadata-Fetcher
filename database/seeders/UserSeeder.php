<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (User::query()->exists()) {
            return;
        }

        User::factory()->create([
            'email' => 'test@test.com',
            'password' => Hash::make('123456')
        ]);

        User::factory()->count(10)->create();
    }
}
