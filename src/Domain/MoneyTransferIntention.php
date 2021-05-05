<?php
declare(strict_types=1);

namespace Tpay\Domain;

class MoneyTransferIntention
{
    private Account $payerAccount;
    private Account $receiverAccount;
    private float $amount;

    public function __construct(Account $payerAccount, Account $receiverAccount, float $amount)
    {
        $this->payerAccount = $payerAccount;
        $this->receiverAccount = $receiverAccount;
        $this->amount = $amount;
    }

    public function getPayerAccount(): Account
    {
        return $this->payerAccount;
    }

    public function getReceiverAccount(): Account
    {
        return $this->receiverAccount;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }
}
