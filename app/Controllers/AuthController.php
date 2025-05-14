<?php

namespace App\Controllers;

use App\Response\JsonResponse;
use Core\Auth\Auth;
use Core\Routing\Controller;
use Core\Http\Request;
use Core\Valid\Validator;
use Firebase\JWT\JWT;

class AuthController extends Controller
{
    public function login(Request $request, JsonResponse $json)
    {
        error_log("Login endpoint hit");
    
        $valid = Validator::make($request->only(['email', 'password']), [
            'email' => ['required', 'str', 'trim', 'min:5', 'max:30'],
            'password' => ['required', 'str', 'trim', 'min:8', 'max:20']
        ]);
    
        if ($valid->fails()) {
            error_log("Validation failed");
            return $json->error($valid->messages(), 400);
        }
    
        if (!Auth::attempt($valid->only(['email', 'password']))) {
            error_log("Auth failed");
            return $json->error(['unauthorized'], 401);
        }
    
        $user = Auth::user();
        if (!$user) {
            error_log("Auth success, but user is null");
            return $json->error(['user not found'], 500);
        }
    
        $jwtKey = env('JWT_KEY');
        if (!$jwtKey) {
            error_log("JWT key is missing");
            return $json->error(['internal error'], 500);
        }
    
        $token = JWT::encode(
            array_merge([
                'iat' => time(),
                'exp' => time() + (60 * 60)
            ], $user->only(['id', 'nama'])->toArray()),
            $jwtKey,
            'HS256'
        );
    
        error_log("Login success");
    
        return $json->success([
            'token' => $token,
            'user' => $user
        ], 200);
    }
}
