<?php

namespace Tests\Api;

use Tests\Support\UserTestBase;
use Codeception\Util\HttpCode;

class ValidationErrorTest extends UserTestBase {

    public function testCreateUserWithInvalidData($I): void
    {
        $I->wantTo('Ensure invalid input is handled correctly');
        $this->setHeaders($I, true);

        $invalidData = [
            ['firstName' => '', 'lastName' => 'Doe'], // Empty firstName 
            ['firstName' => 'John', 'lastName' => '', 'email' => 'john.doe@example.com'], // Empty lastName 
            ['firstName' => 'John', 'lastName' => 'Doe', 'email' => 'invalid-email'], // Invalid email format (should be a valid email)
            ['firstName' => 'John', 'lastName' => 'Doe', 'email' => 'john.doe@example.com', 'dateOfBirth' => 'invalid-date'], // Invalid date format for dateOfBirth (YYYY-MM-DD)
            ['firstName' => 'John', 'lastName' => 'Doe', 'email' => 'john.doe@example.com', 'personalIdDocument' => ['documentId' => 'AB123456', 'countryOfIssue' => 'US', 'validUntil' => 'invalid-date']], // Invalid validUntil date format (YYYY-MM-DD)
            ['firstName' => 'John', 'lastName' => 'Doe', 'email' => 'john.doe@example.com', 'dateOfBirth' => '1985-10-01', 'personalIdDocument' => ['documentId' => 'AB123456', 'countryOfIssue' => 'INVALID', 'validUntil' => '2030-12-31']], // Invalid countryOfIssue (must be an ISO 3166-1 alpha-2 code)
            ['firstName' => 'John', 'lastName' => 'Doe', 'email' => 'john.doe@example.com', 'dateOfBirth' => '1985-10-01', 'personalIdDocument' => ['documentId' => '', 'countryOfIssue' => 'US', 'validUntil' => '2030-12-31']], // Missing documentId (minLength: 5)
            ['firstName' => 'John', 'lastName' => 'Doe', 'email' => 'john.doe@example.com', 'dateOfBirth' => 'invalid-date', 'personalIdDocument' => ['documentId' => 'AB123456', 'countryOfIssue' => 'US', 'validUntil' => '2030-12-31']], // Invalid date format for dateOfBirth (YYYY-MM-DD)
            ['firstName' => 'John', 'lastName' => 'Doe', 'email' => 'john.doe@example.com', 'dateOfBirth' => '1985-10-01', 'personalIdDocument' => ['documentId' => 'AB1', 'countryOfIssue' => 'US', 'validUntil' => '2030-12-31']], // documentId too short (minLength: 5)
            ['firstName' => 'John', 'lastName' => 'Doe', 'email' => 'john.doe@example.com', 'dateOfBirth' => '1985-10-01', 'personalIdDocument' => ['documentId' => 'AB123456', 'countryOfIssue' => 'US', 'validUntil' => '20301231']], // validUntil not in YYYY-MM-DD format
        ];

        // Loop through invalid data and send POST request for each case
        foreach ($invalidData as $data) {
            $I->sendPost($this->apiBasePath, json_encode($data));
            $I->seeResponseCodeIs(HttpCode::BAD_REQUEST); 
            $I->seeResponseIsJson();
            $I->seeResponseContainsJson(['title' => 'Invalid Input']);
        }
    }

    public function testCreateUserWithInvalidEmail($I): void
    {
        $I->wantTo('Ensure user creation fails with an invalid email');
        $this->setHeaders($I, true);

        $invalidData = $this->getValidUserData();
        $invalidData['email'] = 'invalid-email'; // Invalid email format (must match the email format)
        $I->sendPost($this->apiBasePath, json_encode($invalidData));

        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST);
        $I->seeResponseContainsJson(['title' => 'Invalid Input']);
    }

    public function testCreateUserWithInvalidDateOfBirth($I): void
    {
        $I->wantTo('Ensure user creation fails with an invalid date of birth');
        $this->setHeaders($I, true);

        $invalidData = $this->getValidUserData();
        $invalidData['dateOfBirth'] = 'invalid-date'; // Invalid date format for dateOfBirth (should be YYYY-MM-DD)
        $I->sendPost($this->apiBasePath, json_encode($invalidData));

        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST);
        $I->seeResponseContainsJson(['title' => 'Invalid Input']);
    }

    public function testCreateUserWithInvalidValidUntil($I): void
    {
        $I->wantTo('Ensure user creation fails with an invalid validUntil date');
        $this->setHeaders($I, true);

        $invalidData = $this->getValidUserData();
        $invalidData['personalIdDocument']['validUntil'] = 'invalid-date'; // Invalid validUntil format (should be YYYY-MM-DD)
        $I->sendPost($this->apiBasePath, json_encode($invalidData));

        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST);
        $I->seeResponseContainsJson(['title' => 'Invalid Input']);
    }


    public function testCreateUserWithInvalidCountryOfIssue($I): void
    {
        $I->wantTo('Ensure user creation fails with an invalid countryOfIssue');
        $this->setHeaders($I, true);

        $invalidData = $this->getValidUserData();
        $invalidData['personalIdDocument']['countryOfIssue'] = 'INVALID'; // Invalid countryOfIssue (must be ISO 3166-1 alpha-2 code)
        $I->sendPost($this->apiBasePath, json_encode($invalidData));

        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST);
        $I->seeResponseContainsJson(['title' => 'Invalid Input']);
    }

    public function testCreateUserWithMissingDocumentId($I): void
    {
        $I->wantTo('Ensure user creation fails with a missing documentId');
        $this->setHeaders($I, true);

        $invalidData = $this->getValidUserData();
        $invalidData['personalIdDocument']['documentId'] = ''; // Missing documentId (minLength: 5)
        $I->sendPost($this->apiBasePath, json_encode($invalidData));

        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST);
        $I->seeResponseContainsJson(['title' => 'Invalid Input']);
    }

    public function testCreateUserWithShortDocumentId($I): void
    {
        $I->wantTo('Ensure user creation fails with a short documentId');
        $this->setHeaders($I, true);

        $invalidData = $this->getValidUserData();
        $invalidData['personalIdDocument']['documentId'] = 'AB1'; // documentId too short (minLength: 5)
        $I->sendPost($this->apiBasePath, json_encode($invalidData));

        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST);
        $I->seeResponseContainsJson(['title' => 'Invalid Input']);
    }

    public function testCreateUserWithValidData($I): void
    {
        $I->wantTo('Ensure user creation works correctly with valid data');
        $this->setHeaders($I, true);

        $validData = $this->getValidUserData();
        $I->sendPost($this->apiBasePath, json_encode($validData));

        $I->seeResponseCodeIs(HttpCode::CREATED);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(['firstName' => 'John', 'lastName' => 'Doe', 'email' => 'john.doe@example.com']);
    }

    public function testUpdateNonExistingUser($I): void
    {
        $I->wantTo('Ensure updating a non-existing user returns 404');
        $this->setHeaders($I, true);

        $nonExistUserId = '999999';
        $updatedData = ['firstName' => 'Jane'];

        $I->sendPut("{$this->apiBasePath}/{$nonExistUserId}", json_encode($updatedData));
        $I->seeResponseCodeIs(HttpCode::NOT_FOUND);
    }
}

?>
