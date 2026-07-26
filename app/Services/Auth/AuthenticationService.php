<?php

namespace App\Services\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthenticationService
{
    /**
     * Attempt to authenticate a user with the given email and password.
     *
     * @param string $email
     * @param string $password
     * @return User|null
     */
    public function authenticate(string $email, string $password): ?User
    {
        $user = User::where('email', $email)->first();

        if ($user && Hash::check($password, $user->password)) {
            return $user;
        }

        return null;
    }
}