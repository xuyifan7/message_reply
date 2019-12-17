<?php

namespace App\Http\Services\DataService;

use App\Models\UserModel;
use Illuminate\Support\Facades\Hash;

class UserDS
{
    public function addUser(array $request)
    {
        $user = new UserModel;
        $user->name = $request['name'];
        $user->email = $request['email'];
        $user->password = Hash::make($request['password']);
        $user->save();
        return $user->toArray();
    }

}

