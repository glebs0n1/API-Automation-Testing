<?php

namespace Tests\Api;

use Tests\Support\UserTestBase;
use Codeception\Util\HttpCode;

class UpdateUserTest extends UserTestBase {

    public function updateUserDetails(ApiTester $I): void
    {
        $I->wantTo('Update user details');
        $this->setHeaders($I, true);

        $userId = $this->createUser($I, $this->getValidUserData());
        $updatedData = [
            "firstName" => "John",
            "lastName" => "Smith",
            "email" => "john.doe@example.com"
        ];

        $I->sendPut("/users/{$userId}", json_encode($updatedData));
        $I->seeResponseCodeIs(HttpCode::OK); // 200
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson($updatedData);

        // Retrieve the user to confirm updates were persisted
        $I->sendGet("/users/{$userId}");
        $I->seeResponseCodeIs(HttpCode::OK); // 200
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson($updatedData);
}

    public function updateNonExistentUser(ApiTester $I)
    {
        $I->wantTo('Update a non-existent user');
        $this->setHeaders($I, true);

        $nonExistentUserId = '999999';
        $invalidUpdateData = [
            'firstName' => 'John',
            'lastName' => 'Doe',
        ];

        $I->sendPut("/users/{$nonExistentUserId}", json_encode($invalidUpdateData));
        $I->seeResponseCodeIs(HttpCode::NOT_FOUND); // 404
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson([
            'title' => 'User not found',
            'status' => 404,
            'detail' => "User with ID {$nonExistentUserId} does not exist."
        ]);
    }
}
