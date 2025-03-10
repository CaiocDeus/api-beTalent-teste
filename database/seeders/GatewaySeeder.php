<?php

namespace Database\Seeders;

use App\Models\Gateway;
use App\Enums\UserRoles;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;

class GatewaySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Gateway::create([
          'name' => 'Gateway 1', 'is_active' => true, 'priority' => 1
        ]);

        Gateway::create([
          'name' => 'Gateway 2', 'is_active' => true, 'priority' => 2
        ]);
    }
}
