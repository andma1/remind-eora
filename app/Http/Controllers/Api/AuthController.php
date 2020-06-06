<?php


namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\SignInRequest;
use App\Http\Requests\Auth\SignOutRequest;
use App\Http\Requests\Auth\SignUpRequest;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function signUp(SignUpRequest $request): array
    {
        return (new UserResource())->transform($request->signUp()->getCreatedUser());
    }

    public function signIn(SignInRequest $request): array
    {
        $request->signIn();

        return (new UserResource())->transform(Auth::user());
    }

    public function signOut(SignOutRequest $request): array
    {
        $request->signOut();

        return [
            'User successfully logged out'
        ];
    }
}
