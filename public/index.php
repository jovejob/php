<?php

use App\Controller\AccountController;
use App\Controller\CustomerController;
use App\Repository\CustomerRepository;

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../bootstrap.php'; // Update this line for Eloquent setup

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Instantiate the repository and controllers with dependency injection
$customerRepository = new CustomerRepository();
$customerController = new CustomerController($customerRepository);
$accountController = new AccountController($customerRepository);

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

    // todo Account routes

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