<?php

namespace App\Http\Resources;

use App\Http\Requests\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ClassroomResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'participants' => $this->participants()->withCount('images')->get(),
            'images' => $this->images
        ];
    }
}
