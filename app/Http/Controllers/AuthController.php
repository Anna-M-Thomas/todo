<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request){
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return response(Response::HTTP_OK);
        }
     return response()->json(['message' => 'Invalid login details'], 401);
    
    }

    public function logout(Request $request){
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return response(Response::HTTP_OK);
    }

    public function me(Request $request)
    {
        $user = $request->user();
        return response()->json($user);
    }
}