<?php

namespace App\Http\Resources;

use App\Models\Company;
use Illuminate\Http\Resources\Json\JsonResource;

class CompanyResource extends JsonResource
{
    /** @var string|Company */
    public $resource = Company::class;

    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return $this->removeMissingValues([
            'id' => $this->resource['id'],
            'title' => $this->resource['title'],
            'owner_id' => $this->resource['owner_id'],
            'affiliates' => $this->whenLoaded('affiliates', AffiliateResource::collection($this->resource->affiliates)),
        ]);
    }
}
