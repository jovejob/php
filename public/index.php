<?php

use App\BusinessLogic\AccountService;
use App\Controller\AccountController;
use App\Controller\CustomerController;
use App\Repository\CustomerRepository;

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../bootstrap.php';

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Instantiate the repository and controllers with dependency injection
$customerRepository = new CustomerRepository();
$customerController = new CustomerController($customerRepository);
$accountService = new AccountService($customerRepository);
$accountController = new AccountController($accountService);

switch (true) {
    // Handle dynamic customer routes (e.g., /api/customers/1)
    case preg_match('/^\/api\/customers\/(\d+)$/', $uri, $matches) === 1:
        $id = (int)$matches[1]; // Get the customer ID from the regex match
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $customerController->getCustomerById($id);
        } elseif ($_SERVER['REQUEST_METHOD'] === 'PUT') {
            $customerController->updateCustomer($id);
        } elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
            $customerController->deleteCustomer($id);
        }
        break;

    // Handle static customer route (e.g., /api/customers)
    case $uri === '/api/customers':
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $customerController->getCustomers();
        } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $customerController->createCustomer();
        }
        break;

    // Handle dynamic account routes (e.g., /api/accounts/1)
    case preg_match('/^\/api\/accounts\/(\d+)$/', $uri, $matches) === 1:
        $customerId = (int)$matches[1]; // Get the account/customer ID from the regex match
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $accountController->getAccountBalance($customerId);
        }
        break;

    // Handle deposit route (e.g., /api/accounts/1/deposit)
    case preg_match('/^\/api\/accounts\/(\d+)\/deposit$/', $uri, $matches) === 1:
        $customerId = (int)$matches[1]; // Get the account/customer ID from the regex match
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $accountController->deposit($customerId);
        }
        break;

    // Handle withdraw route (e.g., /api/accounts/1/withdraw)
    case preg_match('/^\/api\/accounts\/(\d+)\/withdraw$/', $uri, $matches) === 1:
        $customerId = (int)$matches[1]; // Get the account/customer ID from the regex match
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $accountController->withdraw($customerId);
        }
        break;

    // Handle transfer route (e.g., /api/accounts/transfer)
    case $uri === '/api/accounts/transfer':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $accountController->transfer();
        }
        break;

    default:
        handleNotFound();
        break;
}

// Function to handle 404 Not Found
function handleNotFound()
{
    http_response_code(404);
    echo json_encode(['error' => '404 Not Found']);
    exit;
}
