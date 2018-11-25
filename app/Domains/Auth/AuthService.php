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
            return response([
                'errors' => [
                    'invalid_credentials' => [
                        0 => 'E-mail or password is incorrect.',
                    ],
                ],
            ], 401);
        }

        return response(compact('token'));
    }
}
