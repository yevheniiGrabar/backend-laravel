<?php

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /** @var string|User */
    public $resource = User::class;

    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request |null
     *
     * @return array
     */
    public function toArray($request = null): array
    {
        return [
            'id'           => $this->resource->id,
            'first_name'   => $this->resource->first_name,
            'last_name'    => $this->resource->last_name,
            'email'        => $this->resource->email,
            'phone'        => $this->resource->phone,
            'title'        => $this->resource->fullname,
            'status'       => $this->resource->status,
            'country'      => $this->resource->country,
            'city'         => $this->resource->city,
            'affiliate_id' => $this->resource->affiliate_id
        ];
    }
}
