<?php

namespace Tests\Api;

use Tests\Support\UserTestBase;
use Codeception\Util\HttpCode;

class SecurityTest extends UserTestBase {
    public function testCreateUserUnauthorized($I): void
    {
        $I->wantTo('Ensure unauthorized access is rejected when creating a user');
        $this->setHeaders($I, false); // No authentication header

        $userData = $this->getValidUserData();
        $I->sendPost($this->apiBasePath, json_encode($userData));
        $I->seeResponseCodeIs(HttpCode::UNAUTHORIZED); // 401
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(['title' => 'Unauthorized']);
    }

    public function testUpdateUserUnauthorized($I): void
    {
        $I->wantTo('Ensure unauthorized access is rejected when updating a user');
        $this->setHeaders($I, false); // No authentication header

        $userId = $this->createUser($I);
        $updatedData = ['firstName' => 'Jane'];
        $I->sendPut("/users/{$userId}", json_encode($updatedData));
        $I->seeResponseCodeIs(HttpCode::UNAUTHORIZED); // 401
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(['title' => 'Unauthorized']);
    }

    // Test unauthorized access for Get User
    public function testGetUserUnauthorized($I): void
    {
        $I->wantTo('Ensure unauthorized access is rejected when retrieving a user');
        $this->setHeaders($I, false); 

        $userId = $this->createUser($I);
        $I->sendGet("/users/{$userId}");
        $I->seeResponseCodeIs(HttpCode::UNAUTHORIZED); // 401
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(['title' => 'Unauthorized']);
    }

    public function testDeleteUserUnauthorized($I): void
    {
        $I->wantTo('Ensure unauthorized access is rejected when deleting a user');
        $this->setHeaders($I, false); 

        $userId = $this->createUser($I);
        $I->sendDelete("/users/{$userId}");
        $I->seeResponseCodeIs(HttpCode::UNAUTHORIZED); // 401
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(['title' => 'Unauthorized']);
    }
}


?>