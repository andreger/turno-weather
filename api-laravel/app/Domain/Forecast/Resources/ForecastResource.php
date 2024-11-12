<?php

namespace App\Domain\Forecast\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ForecastResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'temperature' => $this->temperature,
            'description' => $this->description,
            'icon' =>  $this->icon,
            'condition_at' => $this->condition_at,
        ];
    }
}
