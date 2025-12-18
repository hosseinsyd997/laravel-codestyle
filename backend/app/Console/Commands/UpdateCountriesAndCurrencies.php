<?php

namespace App\Console\Commands;

use App\Models\Currency;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Carbon;

class UpdateCountriesAndCurrencies extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:countries-currencies';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch countries and currencies from REST Countries API and update database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            $this->info('Fetching data from REST Countries API...');
            $url = config('app.countries_with_currency_api_url');
            /** @var \Illuminate\Http\Client\Response $response */
            $response = Http::get($url);

            if ($response->failed()) {
                $this->error('API request failed!');
                return 1;
            }

            $data = collect($response->json());
            $now = Carbon::now();

            DB::transaction(function () use ($data, $now) {

                $currencies = $data->pluck('currencies')
                    ->filter()
                    ->flatMap(fn($c) => collect($c)->map(fn($v, $k) => [
                        'code' => $k,
                        'name' => $v['name'] ?? null,
                        'symbol' => $v['symbol'] ?? null,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ]))
                    ->unique('code')
                    ->values()
                    ->all();

                DB::table('currencies')->upsert($currencies, ['code'], ['name', 'symbol']);

                $currencyMap = Currency::pluck('id', 'code');

                $countries = $data->map(fn($item) => [
                    'code' => $item['cca2'] ?? null,
                    'name' => $item['name']['common'] ?? null,
                    'created_at' => $now,
                    'updated_at' => $now,
                    'currency_id' => !empty($item['currencies'])
                        ? $currencyMap[array_key_first($item['currencies'])] ?? null
                        : null,
                ])->all();

                DB::table('countries')->upsert($countries, ['code'], ['name', 'currency_id']);
            });

            $this->info('Countries and currencies updated successfully');
            return 0;
        } catch (\Exception $e) {
            $this->error("Something went wrong: " . $e->getMessage());
            return 1;
        }
    }
}
