<?php

namespace Tests\Feature;

use App\Models\Country;
use App\Models\Currency;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UpdateUserTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_update_user()
    {
        $currency1 = Currency::firstOrCreate(['code' => 'IRR'], ['name' => 'Iranian Rial', 'symbol' => '﷼']);
        $currency2 = Currency::firstOrCreate(['code' => 'EUR'], ['name' => 'Euro', 'symbol' => '€']);

        $country1 = Country::firstOrCreate(['name' => 'Iran'], ['code' => 'IR', 'currency_id' => $currency1->id]);
        $country2 = Country::firstOrCreate(['name' => 'Germany'], ['code' => 'DE', 'currency_id' => $currency2->id]);

        $user = User::factory()->create(['country_id' => $country1->id]);

        $response = $this->putJson("/api/users/{$user->id}", [
            'name' => 'Updated Name',
            'country' => 'germany',
        ]);

        $response->assertOk()
            ->assertJsonPath('data.name', 'Updated Name')
            ->assertJsonPath('data.country.name', 'Germany');

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'country_id' => $country2->id,
        ]);
    }
}
