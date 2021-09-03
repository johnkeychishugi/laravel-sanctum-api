<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $fiels = $this->validate($request,[
            'name' => 'required|string',
            'email' => 'required|string|unique:users,email',
            'password' => 'required|string|confirmed'
        ]);

        $user = User::create([
            'name' => $fiels['name'],
            'email' => $fiels['email'],
            'password' => bcrypt($fiels['password'])

        ]);

        $token = $user->createToken('myapptoken')->plainTextToken;

        $response = [
            'status' => 201,
            'user' => $user,
            'token' => $token
        ];

        return response($response, 201);
    }

    public function login(Request $request)
    {
        $fiels = $this->validate($request,[
            'email' => 'required|string',
            'password' => 'required|string'
        ]);

         $user = User::where('email',$fiels['email'])->first();

         if(!$user || !Hash::check($fiels['password'], $user->password)){
            return response([
                'message' => 'Bed creds'
            ],401);
         }

        $token = $user->createToken('myapptoken')->plainTextToken;

        $response = [
            'status' => 200,
            'user' => $user,
            'token' => $token
        ];

        return response($response, 200);
    }

    public function logout(Request $request)
    {
        auth()->user()->tokens()->delete();

        return [
            'message' => 'Logged out'
        ];
    }
}
