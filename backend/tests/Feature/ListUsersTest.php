<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ListUsersTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_list_users()
    {
        User::factory()->create();
        User::factory()->create();

        $response = $this->getJson('/api/users');

        $response->assertOk()
            ->assertJson(
                fn($json) =>
                $json->has('data', 2)
            );
    }
}
