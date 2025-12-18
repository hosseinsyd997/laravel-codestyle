<?php

namespace App\Http\Resources\Country;

use App\Http\Resources\Currency\DetailCurrencyResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DetailCountryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'       => $this->id,
            'name'     => $this->name,
            'code'     => $this->code,
            'currency' => new DetailCurrencyResource($this->whenLoaded('currency')),
        ];
    }
}
