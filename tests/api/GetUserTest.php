<?php

namespace Tests\Api;

use Tests\Support\UserTestBase;
use Codeception\Util\HttpCode;

class GetUserTest extends UserTestBase {

    public function getUserById($I): void
    {
        $I->wantTo('Retrieve a user by ID');
        $this->setHeaders($I);

        $userData = $this->getValidUserData();
        $userId = $this->createUser($I);
        $I->sendGet("/users/{$userId}");
        $I->seeResponseCodeIs(HttpCode::OK); // 200
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(["id" => $userId]);
    }

    public function getAllUsers($I): void
    {
        $I->wantTo('Retrieve a list of all users');
        $this->setHeaders($I);

        // Ensure there is at least one user
        $this->createUser($I);
        
        $I->sendGet($this->apiBasePath);
        $I->seeResponseCodeIs(HttpCode::OK); // 200
        $I->seeResponseIsJson();
        $I->seeResponseMatchesJsonType([
            ['id' => 'string', 'firstName' => 'string', 'lastName' => 'string']
        ]);
    }

    public function getUserWithInvalidId($I): void
    {
        $I->wantTo('Ensure retrieving a user with an invalid ID returns a proper error');
        $this->setHeaders($I);

        $invalidUserId = '999999';
        $I->sendGet("/users/{$invalidUserId}");
        $I->seeResponseCodeIs(HttpCode::NOT_FOUND); // 404
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(['title' => 'User not found']);
 
    }

    public function getUserWithInvalidData($I)
    {
        $I->wantTo('Ensure invalid user creation is handled properly');
        $this->setHeaders($I);

        $invalidData = ['firstName' => '', 'lastName' => 'Doe'];
        $I->sendGet($this->apiBasePath, json_encode($invalidData));
        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST); // 400
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(["title" => "Invalid Input"]);
    }
}