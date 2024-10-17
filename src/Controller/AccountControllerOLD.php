<?php

namespace App\Controller;

use App\BusinessLogic\AccountService;

class AccountControllerOLD
{
    private AccountService $accountService;

    public function __construct(AccountService $accountService)
    {
        $this->accountService = $accountService;
    }

    public function getBalance(int $customerId): void
    {
        $account = $this->accountService->getBalance($customerId);
        $this->sendJsonResponse($account->toArray());
    }

    private function sendJsonResponse(array $data): void
    {
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    public function deposit(int $customerId): void
    {
        $funds = $this->getRequestBody()['funds'] ?? 0;
        $this->accountService->deposit($customerId, $funds);
        $this->sendNoContentResponse();
    }

    private function getRequestBody(): array
    {
        return json_decode(file_get_contents('php://input'), true) ?? [];
    }

    private function sendNoContentResponse(): void
    {
        http_response_code(204);
        exit;
    }

    public function withdraw(int $customerId): void
    {
        $funds = $this->getRequestBody()['funds'] ?? 0;
        $this->accountService->withdraw($customerId, $funds);
        $this->sendNoContentResponse();
    }

    public function transfer(): void
    {
        $requestBody = $this->getRequestBody();
        $from = $requestBody['from'] ?? 0;
        $to = $requestBody['to'] ?? 0;
        $funds = $requestBody['funds'] ?? 0;

        $this->accountService->transfer($from, $to, $funds);
        $this->sendNoContentResponse();
    }
}
