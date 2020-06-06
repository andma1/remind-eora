<?php


namespace App\Http\Requests\Auth;


use App\Http\Requests\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class SignInRequest extends Request
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'username'  => 'required_without:email|string',
            'email'     => 'required_without:username|email',
            'password'  => 'required'
        ];
    }

    public function signIn(): self
    {
        abort_if(!Auth::attempt($this->validated()), 422, 'Wrong username or password');

        Auth::user()->api_token = Str::random(60);
        Auth::user()->save();

        return $this;
    }
}
