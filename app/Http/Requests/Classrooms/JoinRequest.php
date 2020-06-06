<?php


namespace App\Http\Requests\Classrooms;


use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class JoinRequest extends FormRequest
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

    public function handle(): void
    {
        Auth::user()->update(['classroom_id' => $this->route('classroom')->id]);
    }
}
