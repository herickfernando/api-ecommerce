<?php

namespace Tests;

use App\Domains\User\User;
use App\Exceptions\Handler;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Tymon\JWTAuth\Facades\JWTAuth;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    use RefreshDatabase;

    /** @var User */
    protected $loggedUser;

    private $oldExceptionHandler;

    public function setup()
    {
        parent::setup();
        $this->loggedUser = factory(User::class)->create();
        $this->disableExceptionHandling();
    }

    protected function disableExceptionHandling()
    {
        $this->oldExceptionHandler = $this->app->make(ExceptionHandler::class);
        $this->app->instance(
            ExceptionHandler::class,
            new class extends Handler
            {
                public function __construct()
                {
                }

                public function report(\Exception $e)
                {
                }

                public function render($request, \Exception $e)
                {
                    throw $e;
                }
            }
        );
    }

    public function json($method, $uri, array $data = [], array $headers = [])
    {
        $headers = array_merge($headers, $this->headers());

        return parent::json($method, $uri, $data, $headers);
    }

    protected function headers()
    {
        $token = JWTAuth::fromUser($this->loggedUser);
        JWTAuth::setToken($token);

        return [
            'Accept' => 'application/json',
            'Authorization' => sprintf('Bearer %s', $token),
        ];
    }

    protected function withExceptionHandling()
    {
        $this->app->instance(ExceptionHandler::class, $this->oldExceptionHandler);
        return $this;
    }
}
