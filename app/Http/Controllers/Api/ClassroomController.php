<?php

namespace App\Http\Controllers\Api;

use App\Classroom;
use App\Http\Requests\Classrooms\JoinRequest;
use App\Http\Requests\Request;
use App\Http\Resources\ClassroomResource;
use App\Http\Controllers\Controller;
use App\Http\Requests\Classrooms\StoreRequest;
use Illuminate\Http\Resources\Json\JsonResource;

class ClassroomController extends Controller
{
    public function store(StoreRequest $request): array
    {
        return array_merge($request->handle()->only('id', 'name'), [
            'participants' => [],
            'images' => []
        ]);
    }

    public function show(Request $request, Classroom $classroom): array
    {
        return (new ClassroomResource($classroom))->toArray($request);
    }

    public function join(JoinRequest $request, Classroom $classroom): array
    {
        $request->handle();

        return (new ClassroomResource($classroom))->toArray($request);
    }
}
