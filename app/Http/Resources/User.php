<?php

namespace App\Http\Resources;

use App\Http\Requests\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class User extends JsonResource
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
            'token' => $this->remember_token, // This is done because
            'user' => [
                'id' => $this->id,
                'username' => $this->username,
                'email' => $this->email,
                'classroomId' => $this->classroomId
            ]
        ];
    }
}
