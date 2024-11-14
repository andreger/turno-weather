<?php

namespace App\Domain\User\Repositories;

use App\Domain\User\Models\User;

class UserRepository {

    public function getUserByEmail($email): ?User {
        return User::where("email", $email)->first();
    }
}
