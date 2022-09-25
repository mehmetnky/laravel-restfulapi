<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Order extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'orderCode' => $this->orderCode,
            'productId' => $this->productId,
            'quantity' => $this->quantity,
            'address' => $this->address,
            'shippingDate' => $this->shippingDate,
        ];
    }
}
