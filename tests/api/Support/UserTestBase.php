<?php

namespace Tests\Support;

use Codeception\Module;
use Codeception\Util\HttpCode;
use Tests\Api\ApiTester;

class UserTestBase extends Module{ 
    protected string $apiBasePath = '/api'; // Define your base path here

    protected function setHeaders(ApiTester $I, bool $withAuth = false): void
    {
        $I->haveHttpHeader('Content-Type', 'application/json');
        if ($withAuth) {
            $I->haveHttpHeader('Authorization', 'Basic ' . base64_encode('username:password'));
        }
    }

    protected function getValidUserData(): array
    {
        return [
            'firstName' => 'John',
            'lastName' => 'Doe',
            'email' => 'john.doe@example.com',
            'dateOfBirth' => '1990-01-01',
            'personalIdDocument' => [
                'documentId' => '123456789',
                'countryOfIssue' => 'US',
                'validUntil' => '2030-12-31',
            ],
        ];
    }

    protected function createUser(ApiTester $I): string
    {
        $this->setHeaders($I, true);  
        $userData = $this->getValidUserData();

        $I->sendPost("/users", json_encode($userData));
        $I->seeResponseCodeIs(HttpCode::CREATED); // 201: User created successfully
        $I->seeResponseContainsJson(['message' => 'User created successfully']);
      
        $response = json_decode($I->grabResponse(), true);
        return $response['id'];
    }
}

