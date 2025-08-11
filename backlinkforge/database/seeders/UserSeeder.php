<?php

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
        // Create admin user
        User::factory()->withPersonalTeam()->create([
            'name' => 'Admin User',
            'email' => 'admin@backlinkforge.com',
            'password' => bcrypt('password'),
        ]);

        // Create demo user
        User::factory()->withPersonalTeam()->create([
            'name' => 'Demo User',
            'email' => 'demo@backlinkforge.com',
            'password' => bcrypt('password'),
        ]);

        // Create additional test users
        User::factory(3)->withPersonalTeam()->create();
    }
}
