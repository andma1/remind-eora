<?php


namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\SignInRequest;
use App\Http\Requests\Auth\SignOutRequest;
use App\Http\Requests\Auth\SignUpRequest;

class AuthController extends Controller
{
    public function signUp(SignUpRequest $request): array
    {
        return [
            'user'  => $user = $request->signUp(),
            'token' => $user->api_token // remember token is given by separate key, because authentication type will be changed in future, and authentication token will not be in User model
        ];
    }

    public function signIn(SignInRequest $request): array
    {
        return [
            'user'  => $user = $request->signIn(),
            'token' => $user->api_token // remember token is given by separate key, because authentication type will be changed in future, and authentication token will not be in User model
        ];
    }

    public function signOut(SignOutRequest $request): array
    {
        $request->signOut();

        return [
            'User successfully logged out'
        ];
    }
}
