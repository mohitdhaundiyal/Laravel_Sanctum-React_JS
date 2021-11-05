<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    function register(Request $request)
    {
        // validations
        $field = $request->validate([
            'name' => 'required',
            'email' => 'required|string|unique:users,email',
            'password' => 'required|string|confirmed',
        ]);

        // create user
        $user = User::create([
            'name' => $field['name'],
            'email' => $field['email'],
            'password' => Hash::make($field['password'])
        ]);

        // create token
        $token = $user->createToken('myapptoken')->plainTextToken;

        $response = [
            'message'  => $user,
            'auth-token' => $token
        ];

        return response($response, 201);
    }

    function login(Request $request)
    {
        $field = $request->validate([
            'email' => 'required|string',
            'password' => 'required|string'
        ]);

        // check email
        $user = user::where('email', $field['email'])->first();

        // check password
        if (!$user || !Hash::check($field['password'], $user->password)) {
            return response([
                'message' => 'bad credentials'
            ], 401);
        }

        // create token
        $token = $user->createToken('myapptoken')->plainTextToken;

        $response = [
            'message' => 'Login Successful',
            'auth-token' => $token
        ];
        return response($response, 201);
    }
}
