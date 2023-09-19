<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function login()
    {
        $data = request()->validate([
            "email" => "required|email",
            "password" => "required",
        ]);
        if (Auth::attempt($data)) {
            $user = Auth::user();
            // $data = User::createToken("")->plainTextToken;
            return ["token" => $user->createToken("token")->plainTextToken];
        } else {
            throw ValidationException::withMessages([["email" => "email is not successfully entered"]]);
        }
    }

    public function logout()
    {
        request()->user()->tokens()->delete();
        return response()->json([
            "message" => "Logedout successfully!!"
        ]);
    }
}