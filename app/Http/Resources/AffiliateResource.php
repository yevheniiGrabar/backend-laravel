<?php

namespace App\Http\Resources;

use App\Models\Affiliate;
use Illuminate\Http\Resources\Json\JsonResource;

class AffiliateResource extends JsonResource
{
    /** @var Affiliate|string  */
    public $resource = Affiliate::class;

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return $this->removeMissingValues([
            'id' => $this->id,
            'title' => $this->title,
            'company' => ($this->company) ? $this->company->title : null,
            'departments' => $this->whenLoaded(
                'departments',
                DepartmentResource::collection($this->departments)
            ),
            'courses' => $this->whenLoaded(
                'courses',
                CourseResource::collection($this->courses()->without('courses')->get())
            ),
        ]);
    }
}
