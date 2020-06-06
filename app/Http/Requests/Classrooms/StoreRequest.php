<?php


namespace App\Http\Requests\Classrooms;


use App\Classroom;
use App\Http\Requests\Request;
use Illuminate\Support\Facades\Auth;

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
        $classroom = Classroom::create(array_merge($this->validated(), [
            'dir' => strtolower($this->get('name')) . '-' . str_replace(' ', '', microtime())
        ]));

        Auth::user()->update(['classroom_id' => $classroom->id]);

        return $classroom;
    }
}
