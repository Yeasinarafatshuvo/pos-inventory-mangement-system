<?php

namespace App\Http\Resources;

use App\Http\Resources\OrderProductCollection;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderSingleCollection extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {   
        
        return [
            'id' => $this->id,
            'code' => $this->code,
            'user' => [
                'name' => isset($this->user) == null? '': $this->user->name,
                'email' => isset($this->user) == null? '': $this->user->email,
                'phone' => isset($this->user) == null? '': $this->user->phone,
                'avatar' => isset($this->user) == null? '' : api_asset($this->user->avatar),
            ],
            'shipping_address' => json_decode($this->shipping_address),
            'billing_address' => json_decode($this->billing_address),
            'grand_total' => (double) $this->grand_total,
            'orders' => OrderResource::collection($this->orders),
            'date' => $this->created_at->toFormattedDateString()
        ];
    }
    
    public function with($request)
    {
        return [
            'success' => true,
            'status' => 200
        ];
    }
}