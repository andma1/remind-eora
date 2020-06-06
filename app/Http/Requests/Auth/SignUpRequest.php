<?php


namespace App\Http\Requests\Auth;


use App\Http\Requests\Request;
use App\User;
use Illuminate\Support\Str;

class SignUpRequest extends Request
{
    private $user;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'username'  => 'required|string|min:3',
            'email'     => 'required|email|unique:users,email',
            'password'  => 'required|confirmed|min:6'
        ];
    }

    public function signUp(): self
    {
        $this->user = User::create(array_merge($this->only('username', 'email'), [
            'api_token' => Str::random(60),
            'password' => bcrypt($this->get('password'))
        ]));

        return $this;
    }

    public function getCreatedUser(): User
    {
        return $this->user;
    }
}
