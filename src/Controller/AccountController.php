<?php

namespace App\Controller;

use App\BusinessLogic\AccountService;
use App\DTO\TransactionDTO;

class AccountController
{
    private AccountService $accountService;

    public function __construct(AccountService $accountService)
    {
        $this->accountService = $accountService;
    }

    public function getAccountBalance(int $customerId): void
    {
        $balance = $this->accountService->getAccountBalance($customerId);
        echo json_encode(['balance' => $balance]);
    }

    public function deposit(int $customerId): void
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $transactionDTO = new TransactionDTO($customerId, $data['funds']);
        $result = $this->accountService->deposit($transactionDTO);

        if ($result) {
            echo json_encode(['message' => 'Deposit successful']);
        } else {
            echo json_encode(['error' => 'Failed to deposit'], JSON_PRETTY_PRINT);
        }
    }

    public function withdraw(int $customerId): void
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $transactionDTO = new TransactionDTO($customerId, $data['funds']);
        $result = $this->accountService->withdraw($transactionDTO);

        if ($result) {
            echo json_encode(['message' => 'Withdrawal successful']);
        } else {
            echo json_encode(['error' => 'Failed to withdraw or insufficient funds'], JSON_PRETTY_PRINT);
        }
    }

    public function transfer(): void
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $transactionDTO = new TransactionDTO($data['from'], $data['funds'], $data['to']);
        $result = $this->accountService->transfer($transactionDTO);

        if ($result) {
            echo json_encode(['message' => 'Transfer successful']);
        } else {
            echo json_encode(['error' => 'Failed to transfer or insufficient funds'], JSON_PRETTY_PRINT);
        }
    }
}
