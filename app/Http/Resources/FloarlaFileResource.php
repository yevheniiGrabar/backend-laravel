<?php

namespace App\Http\Resources;

class FloarlaFileResource extends FileResource
{

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $host = env('APP_URL', 'http://localhost');

        return [
            'id' => $this->id,
            'url' => $host . $this->name,
            'link' => $host . $this->name,
            'thumb' => $host . $this->name,
            'name' => $this->name,
        ];
    }
}
