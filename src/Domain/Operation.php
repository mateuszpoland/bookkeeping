<?php
declare(strict_types=1);

namespace Tpay\Domain;

class Operation
{
    private int $operationId;
    private string $accountUid;
    private float $amount;

    public function __construct(int $operationId, string $accountUid,  float $amount)
    {
        $this->operationId = $operationId;
        $this->accountUid = $accountUid;
        $this->amount = $amount;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function getOperationId(): int
    {
        return $this->operationId;
    }

    public function getAccountUid(): string
    {
        return $this->accountUid;
    }
}
