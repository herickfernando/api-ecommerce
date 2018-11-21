<?php

namespace App\Domains\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

/**
 * Class AuthController
 * @package App\Domains\Auth
 * @property AuthService $service
 */
class AuthController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $credentials = [
            'email' => $request->get('email'),
            'password' => $request->get('password'),
        ];

        return $this
            ->service
            ->authenticate($credentials);
    }
}