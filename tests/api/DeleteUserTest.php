<?php

namespace Tests\Api;
use Tests\Support\UserTestBase;
use Codeception\Util\HttpCode;

class DeleteUserTest extends UserTestBase {

    public function testDeleteUserSuccessfully(ApiTester $I): void
    {
        $I->wantTo('Delete an existing user');
        $this->setHeaders($I);

        // Create a new user
        $userId = $this->createUser($I, $this->getValidUserData());

        $I->sendDelete("/users/{$userId}");
        $I->seeResponseCodeIs(HttpCode::NO_CONTENT); // 204 

        // Verify the user is no longer retrievable
        $I->sendGet("/users/{$userId}");
        $I->seeResponseCodeIs(HttpCode::NOT_FOUND); // 404
        $I->seeResponseContainsJson(['title' => 'User not found']);
    }

    public function deleteNonExistentUser(ApiTester $I) : void
    {
        $I->wantTo('Try to delete a non-existent user');
        $this->setHeaders($I);

        $invalidUserId = '999999';
        $I->sendDelete("/users/{$invalidUserId}");// Non-existent ID
        $I->seeResponseCodeIs(HttpCode::NOT_FOUND); 
        $I->seeResponseContainsJson(['title' => 'User not found']);
        }
}
