<?php

namespace App\Tests\Entity;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\User;

class UserEntityTest extends ApiTestCase
{

    private string $jwtToken;


    public static function userLoggedIn(): string
    {
        $response = static::createClient()->request('POST', '/api/login_check', ['json' => [
            'username' => 'marionFa2',
            'password' => 'testPass1',
        ]]);
        return $response->toArray()['token'];
    }

    public function testGetUsersWithouthAuth(): void
    {
        // Test GET /api/users without auth
        $response = static::createClient()->request('GET', '/api/users', ['headers' => ['Accept' => 'application/ld+json']]);
        $this->assertResponseIsSuccessful();
    }

    public function testGetUsersWithAuth(): void
    {

        // Test GET /api/users with auth
        $this->jwtToken = $this->userLoggedIn();

        $response = static::createClient()->request('GET', '/api/users', ['headers' => ['Accept' => 'application/ld+json'], 'auth_bearer' => $this->jwtToken]);
        $this->assertResponseIsSuccessful();
    }

    public function testCreateUser(): void
    {
        // Test POST /api/users with to short username
        $response = static::createClient()->request('POST', '/api/users', ['headers' => ['Accept' => 'application/json'], 'json' => [
            'email' => 'test-new-user@example.com',
            'username' => 'test',
            'password' => 'password',
            'confirmPassword' => 'password',
        ]]);
        $this->assertResponseStatusCodeSame(422);

        // Test POST /api/users with uneligible password
        $response = static::createClient()->request('POST', '/api/users', ['headers' => ['Accept' => 'application/json'], 'json' => [
            'email' => 'test-new-user@example.com',
            'username' => 'testLonger',
            'password' => 'password',
            'confirmPassword' => 'password',
        ]]);
        $this->assertResponseStatusCodeSame(422);

        // Test POST /api/users with right fields
        $response = static::createClient()->request('POST', '/api/users', ['headers' => ['Accept' => 'application/json'], 'json' => [
            'email' => 'test-new-user@example.com',
            'username' => 'testLonger',
            'password' => 'passwordPass1',
            'confirmPassword' => 'passwordPass1',
        ]]);
        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(201);
    }

    // public function testDeleteUser(): void
    // {

    //     // Get users
    //     $users = static::createClient()->request('GET', '/api/users', ['headers' => ['Accept' => 'application/json']])->toArray();
    //     $last_user = array_pop($users);

    //     // Test DELETE /api/user/{id} without auth
    //     $response = static::createClient()->request('DELETE', '/api/user/' . $last_user['@id'], ['headers' => ['Accept' => 'application/json']]);
    //     $this->assertResponseStatusCodeSame(401);
    //     $this->assertResponseHeaderSame('content-type', 'application/json');
    //     $this->assertJsonContains(['code' => 401, 'message' => 'JWT Token not found']);

    //     // Test DELETE /api/users/{id} with auth
    //     $response = static::createClient()->request('DELETE', '/api/user/' . $last_user['@id'], ['headers' => ['Accept' => 'application/json'], 'auth_bearer' => $this->jwtToken]);
    //     $this->assertResponseStatusCodeSame(204);
    //     $this->assertResponseHeaderSame('content-type', 'application/json; charset=utf-8');
    // }

    // public function testGetUserById(): void
    // {
    //     // Get users
    //     $users = static::createClient()->request('GET', '/api/users', ['headers' => ['Accept' => 'application/ld+json']])->toArray();
    //     $first_user = array_shift($users);

    //     // Test GET /api/users/{id} without auth
    //     $response = static::createClient()->request('GET', '/api/user' . $first_user['@id'], ['headers' => ['Accept' => 'application/json']]);
    //     $this->assertResponseStatusCodeSame(200);

    //     // // Test GET /api/users/{id} with auth
    //     // $response = static::createClient()->request('GET', $path . $first_user['id'], ['headers' => ['Accept' => 'application/json'], 'auth_bearer' => $this->jwtToken]);
    //     // $this->assertResponseIsSuccessful();
    //     // $this->assertJsonContains(['id' => $first_user['id'], 'email' => $first_user['email']]);
    // }
}
