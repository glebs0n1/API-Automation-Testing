# API-Automation-Testing
Backend and API Automation Testing

This repository contains automated tests for a User Account API that supports CRUD operations: create, retrieve, update, and delete users. The API follows the OpenAPI specification and is protected with Basic Authentication.

Test Scenarios:
Create User: Test for successful creation of a user
Get User by ID: Test retrieving an existing user by their ID.
Invalid User Creation: Ensure invalid input (e.g., missing required fields).
Update User: Test updating user details and verifying the changes.
Delete User: Test deleting an existing/non existing user and ensuring it’s no longer retrievable.
Completed also: Security tests (e.g., checking unauthorized access) and Validation for different input combinations.
Containerization using Docker for setup.


Requirements
To run the tests, you need the following:

PHP (I've used version 8.4)
Composer (for dependency management)
Codeception (for automated API testing)
Docker (Optional: for containerized setup)
Setup Instructions
Clone the repository:

git clone <your_repository_url>
cd <repository_name>
Install dependencies:
Composer: using following command <composer install> 
Configure the API base URL:

The API’s base URL should be configured in the tests/Support/UserTestBase.php  file or as an environment variable. By default, it’s set to http://localhost:8080/api.


Run the tests:

After installing the dependencies and configuring the API URL, you can run the automated tests using the Codeception command:
 using following command <vendor/bin/codecept run api> 

This command will execute the API tests and show the test results in your terminal.



Test Scenarios:

The test cases are located in the tests/api directory. Each test corresponds to a specific API operation such as creating, retrieving, updating, or deleting a user.

Example tests include:

CreateUserTest.php: Tests creating a user with valid data.
GetUserTest.php: Tests getting a user by ID.
UpdateUserTest.php: Tests updating user details.
DeleteUserTest.php: Tests deleting a user.
Additional Information
Test Data: Test data (e.g., user information) can be configured in the tests/Support/UserTestBase.php file.

Test Reports: The results of the tests are displayed in the terminal by default.

