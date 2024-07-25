<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => intval($this->id),
            'space_id' => intval($this->space_id),
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
            'space' => new SpaceResource($this->space)
        ];
    }
}
