<?php

namespace Tests\Feature\Controller;

use App\Domains\User\User;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{

    public function testMustLoginWithValidUser()
    {
        factory(User::class)->create(['email' => 'teste@email.com']);
        $payload = [
            'email' => 'teste@email.com',
            'password' => 'secret',
        ];

        $this
            ->post('api/auth', $payload)
            ->assertSuccessful()
            ->assertJsonStructure([
                'token',
            ]);
    }

    public function testMustLoginWithInvalidEmail()
    {
        factory(User::class)->create(['email' => 'teste@email.com']);
        $payload = [
            'email' => 'testeinvalid@email.com',
            'password' => 'secret',
        ];

        $this
            ->post('api/auth', $payload)
            ->assertStatus(401)
            ->assertJson([
                'errors' => 'invalid_credentials',
            ]);
    }

    public function testMustLoginWithInvalidPassword()
    {
        factory(User::class)->create(['email' => 'teste@email.com']);
        $payload = [
            'email' => 'teste@email.com',
            'password' => 'secret123',
        ];

        $this
            ->post('api/auth', $payload)
            ->assertStatus(401)
            ->assertJson([
                'errors' => 'invalid_credentials',
            ]);
    }
}
