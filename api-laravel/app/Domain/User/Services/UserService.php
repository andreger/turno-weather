<?php

namespace App\Domain\User\Services;

use App\Domain\User\Repositories\UserRepository;
use App\Domain\User\Models\User;

class UserService {

    protected UserRepository $userRepository;

    public function __construct(UserRepository $userRepository) {
        $this->userRepository = $userRepository;
    }

    public function getUserByEmail($email):?User {
        return $this->userRepository->getUserByEmail($email);
    }

    public function createAuthToken($user): string {
        return $user->createToken('auth-token')->plainTextToken;
    }

    public function deleteTokens($user) {
        $user->tokens()->delete();
    }
}
