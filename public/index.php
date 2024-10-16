<?php

use App\Controller\CustomerController;

// Adjust the path to the autoload file
require __DIR__ . '/../vendor/autoload.php';

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

$customerController = new CustomerController();

switch ($uri) {
    case '/api/customers':
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $customerController->getCustomers();
        } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $customerController->createCustomer();
        }
        break;
    case preg_match('/^\/api\/customers\/(\d+)$/', $uri, $matches) === 1:
        $id = $matches[1]; // Get the customer ID from the regex match
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $customerController->getCustomerById($id);
        } elseif ($_SERVER['REQUEST_METHOD'] === 'PUT') {
            $customerController->updateCustomer($id);
        } elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
            $customerController->deleteCustomer($id);
        }
        break;
    default:
        header("HTTP/1.1 404 Not Found");
        echo "404 Not Found";
        break;
}
