<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class TripTicketsShortResource extends JsonResource {
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray( $request ) {
        return [
            'id'     => $this->id,
            'car'    => $this->car->name,
            'driver' => $this->driver->name,
            'date' => Carbon::parse($this->created_at)->format('Y-m-d')
        ];
    }
}
