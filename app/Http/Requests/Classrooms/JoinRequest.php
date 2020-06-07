<?php


namespace App\Http\Requests\Classrooms;


use App\Http\Requests\Request;
use Illuminate\Support\Facades\Auth;

class JoinRequest extends Request
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            //
        ];
    }

    public function handle(): void
    {
        Auth::user()->update(['classroom_id' => $this->route('classroom')->id]);
    }
}
