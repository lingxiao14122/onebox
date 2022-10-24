<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::factory()->create([
            'name' => 'admin1',
            'email' => 'admin1@example.com',
            'is_admin' => true,
        ]);
        User::factory()->create([
            'name' => 'user1',
            'email' => 'user1@example.com',
            'is_admin' => false,
        ]);
    }
}
