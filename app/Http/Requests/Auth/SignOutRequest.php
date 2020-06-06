<?php


namespace App\Http\Requests\Auth;


use App\Http\Requests\Request;
use Illuminate\Support\Facades\Auth;

class SignOutRequest extends Request
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

    public function signOut(): void
    {
        Auth::user()->api_token = null;
        Auth::user()->save();
    }
}
