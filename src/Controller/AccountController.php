<?php

namespace App\Controller;

use App\BusinessLogic\AccountService;
use App\DTO\TransactionDTO;
use Exception;

class AccountController
{
    private AccountService $accountService;

    public function __construct(AccountService $accountService)
    {
        $this->accountService = $accountService;
    }

    public function getAccountBalance(int $customerId): void
    {
        try {
            $balance = $this->accountService->getAccountBalance($customerId);
            http_response_code(200);
            echo json_encode(['balance' => $balance], JSON_PRETTY_PRINT);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()], JSON_PRETTY_PRINT);
        }
    }

    public function deposit(int $customerId): void
    {
        try {
            $data = json_decode(file_get_contents('php://input'), true);
            if (!isset($data['funds']) || $data['funds'] <= 0) {
                throw new Exception('Invalid deposit amount.');
            }

            $transactionDTO = new TransactionDTO($customerId, $data['funds']);
            $result = $this->accountService->deposit($transactionDTO);

            if ($result) {
                http_response_code(200);
                echo json_encode(['message' => 'Deposit successful'], JSON_PRETTY_PRINT);
            }
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode(['error' => $e->getMessage()], JSON_PRETTY_PRINT);
        }
    }

    public function withdraw(int $customerId): void
    {
        try {
            $data = json_decode(file_get_contents('php://input'), true);
            if (!isset($data['funds']) || $data['funds'] <= 0) {
                throw new Exception('Invalid withdrawal amount.');
            }

            $transactionDTO = new TransactionDTO($customerId, $data['funds']);
            $result = $this->accountService->withdraw($transactionDTO);

            if ($result) {
                http_response_code(200);
                echo json_encode(['message' => 'Withdrawal successful'], JSON_PRETTY_PRINT);
            }
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode(['error' => $e->getMessage()], JSON_PRETTY_PRINT);
        }
    }

    public function transfer(): void
    {
        try {
            $data = json_decode(file_get_contents('php://input'), true);
            if (!isset($data['from']) || !isset($data['to']) || $data['funds'] <= 0) {
                throw new Exception('Invalid transfer data.');
            }

            $transactionDTO = new TransactionDTO($data['from'], $data['funds'], $data['to']);
            $result = $this->accountService->transfer($transactionDTO);

            if ($result) {
                http_response_code(200);
                echo json_encode(['message' => 'Transfer successful'], JSON_PRETTY_PRINT);
            }
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode(['error' => $e->getMessage()], JSON_PRETTY_PRINT);
        }
    }
}
