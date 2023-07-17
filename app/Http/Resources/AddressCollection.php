<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Models\User;

class AddressCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return [
            'data' => $this->collection->map(function($data) {
                return [
                    'id'      => $data->id,
                    'user_id' => $data->user_id,
                    'user_name' => User::select('name')->where('id',  $data->user_id)->first()['name'],
                    'address' => $data->address,
                    'country' => $data->country,
                    'country_id' => $data->country_id,
                    'city' => $data->city,
                    'city_id' => $data->city_id,
                    'state' => $data->state,
                    'state_id' => $data->state_id,
                    'postal_code' => $data->postal_code,
                    'phone' => $data->phone,
                    'default_shipping' => $data->default_shipping,
                    'default_billing' => $data->default_billing
                ];
            })
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
