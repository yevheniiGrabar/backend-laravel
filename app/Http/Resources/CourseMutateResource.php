<?php

namespace App\Http\Resources;

use App\Models\Course;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class CourseMutateResource extends JsonResource
{

    /**
     * Transform the resource into an array.
     *
     * @param  null|\Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request = null)
    {
        return $this->removeMissingValues([
            'company_id' => Auth::user()->companies()->first()->id, //TODO: Temporary solution
            'department_id' => $this->when(
                !empty($this->resource['department_id']),
                $this->resource['department_id'] ?? null
            ),
            'title' => $this->when(!empty($this->resource['title']), $this->resource['title'] ?? null),
            'description' => $this->when(
                !empty($this->resource['description']),
                $this->resource['description'] ?? null
            ),
            'logo' => $this->when(!empty($this->additional['logo']), $this->additional['logo'] ?? null),
            'status' => $this->when(
                !empty($this->resource['status']),
                $this->resource['status'] ?? Course::STATUS_ENABLED
            ),
        ]);
    }
}
