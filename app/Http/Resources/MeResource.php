<?php

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;

class MeResource extends JsonResource
{
    /** @var string|User */
    public $resource = User::class;

    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request |null
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request = null)
    {
        return [
            'data' => $this->removeMissingValues([
                'id' => $this->resource->id,
                'email' => $this->resource->email,
                'first_name' => $this->resource->first_name,
                'last_name' => $this->resource->last_name,
                'created_at' => $this->resource->created_at,
                'updated_at' => $this->resource->updated_at,
                $this->mergeWhen(!empty($this->additional), $this->additional)
            ])
        ];
    }
}
