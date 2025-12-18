<?php

namespace Tests\Feature;

use App\Models\Country;
use App\Models\Currency;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class StoreUserTest extends TestCase
{
    public function test_can_create_user()
    {
        $currency = Currency::factory()->create(['code' => 'IRR']);
        $country  = Country::factory()->create([
            'name' => 'Iran',
            'currency_id' => $currency->id,
        ]);

        $response = $this->postJson('/api/users', [
            'name'     => 'Hossein',
            'email'    => 'hossein@test.com',
            'country'  => 'iran'
        ]);

        $response->assertCreated();

        $this->assertDatabaseHas('users', [
            'email' => 'hossein@test.com',
            'country_id' => $country->id,
        ]);
    }
}
