# API-Automation-Testing
### Backend and API Automation Testing

### This repository contains automated tests for a User Account API that supports CRUD operations: create, retrieve, update, and delete users. The API follows the OpenAPI specification and is protected with Basic Authentication.

## Test Scenarios:
<ul>
<li>Create User: Test for successful creation of a user</li>
<li>Get User by ID: Test retrieving an existing user by their ID.</li>
<li>Invalid User Creation: Ensure invalid input (e.g., missing required fields).</li>
<li>Update User: Test updating user details and verifying the changes.</li>
<li>Delete User: Test deleting an existing/non existing user and ensuring it’s no longer retrievable.</li>
<li>Completed also: Security tests (e.g., checking unauthorized access) and Validation for different input combinations.</li>
<li>Containerization using Docker for setup.</li>
</ul>

## Requirements
To run the tests, you need the following:
### PHP (I've used version 8.4)
<li>Composer (for dependency management)</li>
<li>Codeception (for automated API testing)</li>
<li>Docker (Optional: for containerized setup).</li>

## Setup Instructions
### Clone the repository: 
git clone <your_repository_url>
cd <repository_name>
## Install dependencies:
Composer: using following command <composer install> 
Configure the API base URL:

### The API’s base URL should be configured in the tests/Support/UserTestBase.php  file or as an environment variable. By default, it’s set to http://localhost:8080/api.


### Run the tests:
After installing the dependencies and configuring the API URL, you can run the automated tests using the Codeception command: using following command <vendor/bin/codecept run api> 

This command will execute the API tests and show the test results in your terminal.



### Test Scenarios:

The test cases are located in the tests/api directory. Each test corresponds to a specific API operation such as creating, retrieving, updating, or deleting a user.

Example tests include:
<ul>
<li>CreateUserTest.php: Tests creating a user with valid data. </li>
<li>GetUserTest.php: Tests getting a user by ID.</li>
<li>UpdateUserTest.php: Tests updating user details.</li>
<li>DeleteUserTest.php: Tests deleting a user.</li>
<li>Additional Information</li>
<li>Test Data: Test data (e.g., user information) can be configured in the tests/Support/UserTestBase.php file.</li>
</ul>
### Test Reports: The results of the tests are displayed in the terminal by default.

