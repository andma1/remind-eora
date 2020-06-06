<?php


namespace App\Http\Requests\Classrooms;


use App\Classroom;
use App\Http\Requests\Request;

class StoreRequest extends Request
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'  => 'required|min:2',
        ];
    }

    public function handle(): Classroom
    {
        return Classroom::create(array_merge($this->validated(), [
            'dir' => $this->get('name') . '-' . microtime()
        ]));
    }
}
