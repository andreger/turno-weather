<?php

namespace App\Domain\Location\Resources;

use App\Domain\Forecast\Resources\ForecastResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LocationResource extends JsonResource
{
    /**
     * Transform the location resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'city' => $this->city,
            'state' => $this->state,
            'forecasts' => ForecastResource::collection($this->forecasts),
        ];
    }
}
