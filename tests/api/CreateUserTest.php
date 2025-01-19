<?php

namespace Tests\Api;

use Tests\Support\UserTestBase;
use Codeception\Util\HttpCode; 

class CreateUserTest extends UserTestBase {

    public function testCreateUserSuccessfully($I): void {
        $I->wantTo('Create a user successfully with valid data');
        $this->setHeaders($I);

        $userData = $this->getValidUserData();
        $I->sendPost($this->apiBasePath . '/users', json_encode($userData));
        $I->seeResponseCodeIs(HttpCode::CREATED); // 201
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson($userData);
        $I->seeResponseMatchesJsonType([
            'id' => 'string',
            'firstName' => 'string',
            'lastName' => 'string',
            'email' => 'string:email',
            'dateOfBirth' => 'string:date',
            'personalIdDocument' => [
                'documentId' => 'string',
                'countryOfIssue' => 'string',
                'validUntil' => 'string:date',
            ],
        ]);
    }
}
