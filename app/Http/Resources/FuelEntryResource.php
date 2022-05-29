<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class FuelEntryResource extends JsonResource
{
    /**
     * Transform the resource with information
     * of one trip into an array.
     *
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'id'        => $this->id,
            'fuel_type' => $this->fuel_type,
            'amount'    => $this->amount,
            'date'      => Carbon::parse($this->created_at)->format('Y-m-d'),
        ];
    }
}
