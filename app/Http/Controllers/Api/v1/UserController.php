<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\v1\User as UserResource;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function login(Request $request){


        $user = User::where('email', $request->email)->first();

        if(!$user || !Hash::check($request->password, $user->password)){
            return response([
                'message'=> [
                    'اطلاعات صحیح نیست.'
                ]
            ], 404);
        }
        return new UserResource($user);
    }

    public function register(Request $request){

        $validation = $this->validate($request, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
        ]);

        $user = User::create([
            'name' => $validation['name'],
            'email' => $validation['email'],
            'password' => $validation['password']
        ]);

        return new UserResource($user);
    }
}
