<?php

namespace App\Http\Resources;

use App\Models\Course;
use Illuminate\Http\Resources\Json\JsonResource;

class CourseResource extends JsonResource
{

    public $resource = Course::class;

    public function toArray($request)
    {

        return [
            'id' => $this->resource->id,
            'title' => $this->resource->title,
            'company' => $this->whenLoaded(
                'company',
                new CompanyResource($this->resource->company()->without('affiliates')->first())
            ),
            'department_id' => $this->resource->department_id,
            'department' => $this->whenLoaded('department', new DepartmentResource($this->resource->department)),
            'moderators'  => $this->whenLoaded('moderators', UserResource::collection($this->resource->moderators)),
            'affiliates' => $this->whenLoaded(
                'affiliates',
                AffiliateResource::collection($this->resource->affiliates()->without('courses')->get())
            ),
            'logo_url' => $this->resource->logo_url,
            'description' => $this->resource->description,
            'status' => $this->resource->status,
        ];
    }
}
