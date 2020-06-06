<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Classrooms\StoreRequest;
use Illuminate\Http\Request;

class ClassroomController extends Controller
{
    public function store(StoreRequest $request): array
    {
        return array_merge($request->handle()->only('name'), [
            'participants' => [],
            'images' => []
        ]);
    }
}
