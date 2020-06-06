<?php


namespace App\Http\Resources;


use App\User;

class UserResource
{
    public function transform(User $user): array
    {
        return [
            'user'      => $user->load('images'),
            'token'     => $user->api_token // remember
        ];
    }
}
