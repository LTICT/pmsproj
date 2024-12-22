<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use PHPOpenSourceSaver\JWTAuth\Exceptions\JWTException;
use PHPOpenSourceSaver\JWTAuth\Exceptions\TokenExpiredException;
use PHPOpenSourceSaver\JWTAuth\Exceptions\TokenInvalidException;
class TokenController extends Controller
{
public function validateToken(Request $request)
    {
        try {
            $token = $request->bearerToken();

            if (!$token) {
                return response()->json(['error' => 'Token not provided'], 401);
            }

            $user = JWTAuth::parseToken()->authenticate();
            return response()->json([
            'status'=> 'success',
            'user'=> $user,
            'authorization'=> [
                'token' => $token,
                'type' => 'bearer'
            ]
        ]);
        }
        catch (TokenInvalidException $e) {
            return response()->json(['error' => 'Token is invalid'], 401);
        } catch (TokenExpiredException $e) {
            return response()->json(['error' => 'Token has expired'], 401);
        } catch (JWTException $e) {
            return response()->json(['error' => 'Token could not be verified'], 401); 
        }
    }
    public function refreshToken(Request $request)
    {
        try {
            $refreshToken = $request->bearerToken();
            $refreshToken = JWTAuth::getToken();
            if (!$refreshToken) {
                return response()->json(['error' => 'No token provided'], 403);
            }

            $newToken = JWTAuth::refresh($refreshToken);

            return response()->json([
                'status' => 'success',
                'token' => $newToken,
                'type' => 'bearer'
            ], 200);

        } catch (JWTException $e) {
            return response()->json(['error' => 'Invalid refresh token'], 403);
        } catch (TokenInvalidException $e) {
            return response()->json(['error' => 'Refresh token is invalid'], 403);
        } catch (TokenExpiredException $e) {
            return response()->json(['error' => 'Refresh token has expired'], 403);
        }
    }

}