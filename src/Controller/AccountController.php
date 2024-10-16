<?php

namespace App\Controller;

use App\Service\AccountService;

class AccountController
{
    private AccountService $accountService;

    public function __construct()
    {
        $this->accountService = new AccountService();
    }

    public function getAccountBalance($id)
    {
        $balance = $this->accountService->getBalance($id);
        header('Content-Type: application/json');
        echo json_encode(['balance' => $balance]);
    }

    public function deposit($id)
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $this->accountService->deposit($id, $data['funds']);
        header('HTTP/1.1 204 No Content');
    }

    public function withdraw($id)
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $this->accountService->withdraw($id, $data['funds']);
        header('HTTP/1.1 204 No Content');
    }

    public function transfer()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $this->accountService->transfer($data['from'], $data['to'], $data['funds']);
        header('HTTP/1.1 204 No Content');
    }
}
