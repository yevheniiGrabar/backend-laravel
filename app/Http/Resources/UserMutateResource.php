<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserMutateResource extends JsonResource
{
    public function toArray($request = null)
    {
        return $this->removeMissingValues([
            'first_name' => $this->when(!empty($this->resource['first_name']), $this->resource['first_name'] ?? null),
            'last_name' => $this->when(!empty($this->resource['last_name']), $this->resource['last_name'] ?? null),
            'phone' => $this->when(!empty($this->resource['phone']), $this->resource['phone'] ?? null),
            'email' => $this->when(!empty($this->resource['email']), $this->resource['email'] ?? null),
            'password' => $this->when(!empty($this->resource['password']), $this->resource['password'] ?? null),
            'position' => $this->when(!empty($this->resource['position']), $this->resource['position'] ?? null),
            'country' => $this->when(!empty($this->resource['country']), $this->resource['country'] ?? null),
            'city' => $this->when(!empty($this->resource['city']), $this->resource['city'] ?? null),
            'avatar' => $this->when(!empty($this->additional['avatar']), $this->additional['avatar'] ?? null),
        ]);
    }
}
