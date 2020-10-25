<?php

namespace App\Services\User;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserService
{
    /**
     * Create a new user.
     *
     * @param Request $request
     *
     * @return User
     */
    public function createUser(Request $request): User
    {
        $user = new User;

        $user->first_name = $request->get('first_name');
        $user->last_name = $request->get('last_name');
        $user->email = $request->get('email');
        $user->password = Hash::make($request->get('password'));

        $user->save();

        return $user;
    }
}
