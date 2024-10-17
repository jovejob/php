<?php

namespace App\Controller;

use App\BusinessLogic\AccountService;
use App\Repository\CustomerRepository;

class AccountController
{
    private AccountService $accountService;

    // Accepting the CustomerRepository as a dependency
    public function __construct(CustomerRepository $repository)
    {
        $this->accountService = new AccountService($repository); // Pass the repository to the service
    }

    public function getAccountBalance(int $customerId)
    {
        $balance = $this->accountService->getAccountBalance($customerId);
        header('Content-Type: application/json');
        echo json_encode(['balance' => $balance]);
    }

    public function deposit(int $customerId)
    {
        $data = json_decode(file_get_contents("php://input"), true);
        $funds = $data['funds'] ?? 0;

        if ($funds <= 0) {
            http_response_code(400);
            echo json_encode(['error' => 'Funds must be greater than zero.']);
            return;
        }

        $this->accountService->deposit($customerId, $funds);
        http_response_code(200);
        echo json_encode(['message' => 'Deposit successful.']);
    }

    public function withdraw(int $customerId)
    {
        $data = json_decode(file_get_contents("php://input"), true);
        $funds = $data['funds'] ?? 0;

        if ($funds <= 0) {
            http_response_code(400);
            echo json_encode(['error' => 'Funds must be greater than zero.']);
            return;
        }

        $this->accountService->withdraw($customerId, $funds);
        http_response_code(200);
        echo json_encode(['message' => 'Withdrawal successful.']);
    }

    public function transfer()
    {
        $data = json_decode(file_get_contents("php://input"), true);
        $from = $data['from'] ?? 0;
        $to = $data['to'] ?? 0;
        $funds = $data['funds'] ?? 0;

        if ($funds <= 0) {
            http_response_code(400);
            echo json_encode(['error' => 'Funds must be greater than zero.']);
            return;
        }

        $this->accountService->transfer($from, $to, $funds);
        http_response_code(200);
        echo json_encode(['message' => 'Transfer successful.']);
    }
}
