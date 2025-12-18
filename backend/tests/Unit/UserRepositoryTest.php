<?php

namespace Tests\Unit;

use App\Models\User;
use App\Models\Country;
use App\Repositories\UserRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserRepositoryTest extends TestCase
{
    use RefreshDatabase;

    protected UserRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = new UserRepository();
    }

    public function test_all_returns_filtered_users_by_country()
    {
        $iran = Country::factory()->create(['name' => 'Iran']);
        $germany = Country::factory()->create(['name' => 'Germany']);

        $user1 = User::factory()->create(['country_id' => $iran->id]);
        $user2 = User::factory()->create(['country_id' => $germany->id]);

        $users = $this->repository->all(['country' => 'Iran']);

        $this->assertCount(1, $users);
        $this->assertEquals('Iran', $users->first()->country->name);
    }

    public function test_all_sorts_users_by_name()
    {
        $country = Country::factory()->create();

        $user1 = User::factory()->create(['name' => 'Charlie', 'country_id' => $country->id]);
        $user2 = User::factory()->create(['name' => 'Alice', 'country_id' => $country->id]);
        $user3 = User::factory()->create(['name' => 'Bob', 'country_id' => $country->id]);

        $users = $this->repository->all([], 'name');

        $names = $users->pluck('name')->toArray();
        $this->assertEquals(['Alice', 'Bob', 'Charlie'], $names);
    }
}
