<?php

namespace App\Http\Controllers;

use App\Domain\User\Resources\UserResource;
use App\Domain\User\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Exception\HttpException;

class AuthController extends BaseController
{
    protected $userService;

    public function __construct(UserService $userService) {
        $this->userService = $userService;
    }

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

        $user = $this->userService->getUserByEmail($request->email);
        $token = $this->userService->createAuthToken($user);

        return $this->sendResponse([
            'token' => $token,
            'user'=> new UserResource($user),
        ]);
    }

    public function logout(Request $request)
    {
        $user = $request->user();
        $this->userService->deleteTokens($user);
        return $this->sendNoContentResponse();
    }
}
