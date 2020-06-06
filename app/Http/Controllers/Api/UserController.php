<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\StoreImageRequest;

class UserController extends Controller
{
    public function storeImage(StoreImageRequest $request): array
    {
        return $request->handle()->only('id', 'name');
    }
}
