<?php

namespace App\Domains\Auth;

use Tymon\JWTAuth\Facades\JWTAuth;

class AuthService
{
    /**
     * @param $credentials
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function authenticate($credentials)
    {
        if (!$token = JWTAuth::attempt($credentials)) {
            return response(['errors' => 'invalid_credentials'], 401);
        }

        return response(compact('token'));
    }
}