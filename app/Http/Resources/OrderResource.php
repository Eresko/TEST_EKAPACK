<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'customer' => new CustomerResource($this->whenLoaded('customer')),
            'status' => $this->status,
            'total_amount' => $this->total_amount,
            'confirmed_at' => $this->confirmed_at ? $this->confirmed_at->toDateTimeString() : null,
            'shipped_at' => $this->shipped_at ? $this->shipped_at->toDateTimeString() : null,
            'created_at' => $this->created_at ? $this->created_at->toDateTimeString() : null,
            'updated_at' => $this->updated_at ? $this->updated_at->toDateTimeString() : null,
            'items' => OrderItemResource::collection($this->whenLoaded('items')),
        ];
    }
}