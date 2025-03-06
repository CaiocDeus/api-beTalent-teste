<?php

namespace Database\Seeders;

use App\Models\User;
use App\Enums\UserRoles;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()
            ->count(12)
            ->state(new Sequence(
                ['role' => UserRoles::Admin],
                ['role' => UserRoles::Manager],
                ['role' => UserRoles::Finance],
                ['role' => UserRoles::User],
            ))
            ->create();
    }
}
