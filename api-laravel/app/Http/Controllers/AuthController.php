<?php

namespace App\Http\Controllers;

use App\Domain\User\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Domain\User\Models\User;
use Symfony\Component\HttpKernel\Exception\HttpException;

class AuthController extends BaseController
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only(['email', 'password']);

        if (!Auth::attempt($credentials)) {
            throw new HttpException(422, 'Invalid credentials');
        }

        $user = User::where('email', $request->email)->first();
        $token = $user->createToken('auth-token')->plainTextToken;

        return $this->sendResponse([
            'token' => $token,
            'user'=> new UserResource($user),
        ]);
    }

    public function logout(Request $request)
    {
        $user = $request->user();
        $user->tokens()->delete();

        return $this->sendNoContentResponse();
    }
}
