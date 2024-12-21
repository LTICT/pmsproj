<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class RefreshTokenController extends Controller
{
    public function refresh(Request $request)
    {
        $refreshToken = $request->bearerToken();
        $refreshToken = JWTAuth::getToken();
//dd($refreshToken);
        try {
            $token = JWTAuth::refresh($refreshToken);
            //dd($token);
            //return response()->json(['token' => $token]);
            return response()->json([
            'status'=> 'success',
            'authorization'=> [
                'token' => $token,
                'type' => 'bearer'
            ]
        ]);
        } catch (JWTException $e) {
            return response()->json(['error' => 'Invalid refresh token'], 401);
        }
    }
}