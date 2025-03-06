<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_that_true_is_true(): void
    {
        $this->assertTrue(true);
    }

    public function test_task_list_can_be_retrieved(): void
    {
        Sanctum::actingAs(
            User::factory()->create(),
            ['view-tasks']
        );

        // Sanctum::actingAs(
        //     User::factory()->create(),
        //     ['*']
        // );

        // $response = $this->get('/api/task');

        // $response->assertOk();
    }
}
