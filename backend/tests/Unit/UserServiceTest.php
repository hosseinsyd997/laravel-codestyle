<?php

namespace Tests\Unit;

use App\Models\User;
use App\Models\Country;
use App\Services\UserService;
use App\Repositories\UserRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserServiceTest extends TestCase
{
    use RefreshDatabase;

    protected UserService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $repository = new UserRepository();
        $this->service = new UserService($repository);
    }

    public function test_store_user_creates_user()
    {
        $country = Country::factory()->create(['name' => 'Iran']);


        $data = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'country' => 'Iran'
        ];

        $user = $this->service->storeUser($data);

        $this->assertInstanceOf(User::class, $user);
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'email' => 'john@example.com',
        ]);
    }

    public function test_update_user_changes_name()
    {
        $user = User::factory()->create(['name' => 'Old Name']);

        $updated = $this->service->updateUser($user, ['name' => 'New Name']);

        $this->assertEquals('New Name', $updated->name);
        $this->assertDatabaseHas('users', ['id' => $user->id, 'name' => 'New Name']);
    }

    public function test_delete_user_removes_from_db()
    {
        $user = User::factory()->create();

        $this->service->deleteUser($user);

        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }
}
