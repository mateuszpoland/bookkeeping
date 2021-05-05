<?php
declare(strict_types=1);

namespace Tpay\Service;

use Tpay\Domain\MoneyTransferIntention;
use Tpay\Domain\Operation;
use Tpay\Domain\Exception\InvalidOperationException;

class Ledger
{
    private array $operationsList;
    private int $currentOperationId;

    public function __construct()
    {
        $this->currentOperationId = 0;
        $this->operationsList = [];
    }

    public function transferMoney(MoneyTransferIntention $moneyTransferIntention)
    {
        $moneySubtractedFromAccountOperation = $this->createMoneySubtractOperation($this->currentOperationId, $moneyTransferIntention);

        $moneyAddedToAccountOperation = new Operation(
            $this->currentOperationId,
            $moneyTransferIntention->getReceiverAccount()->getUid(),
            $moneyTransferIntention->getAmount()
        );

        if(($moneySubtractedFromAccountOperation->getAmount() + $moneyAddedToAccountOperation->getAmount()) !== (float)0) {
            throw InvalidOperationException::transferredAmountsNotEqual();
        }

        $this->operationsList[] = $moneySubtractedFromAccountOperation;
        $this->operationsList[] = $moneyAddedToAccountOperation;
        $this->currentOperationId ++;
    }

    public function getAccountBalanceForOperationId(string $accountIdentifier, int $upToOperationId)
    {
        return $this->getAccountBalance($accountIdentifier, $upToOperationId);
    }

    public function getAccountBalance(string $accountIdentifier, ?int $upToOperationId = null): float
    {
        $accountBalance = 0;
        foreach($this->operationsList as $operation) {
            if($operation->getAccountUid() === $accountIdentifier) {
                $accountBalance += $operation->getAmount();
            }

            if($operation->getOperationId() === $upToOperationId) {
                return $accountBalance;
            }
        }

        return $accountBalance;
    }

    public function getTotalOperationBalance(?int $upToOperationId = null): float
    {
        $total = 0;
        foreach ($this->operationsList as $operation) {
            if($upToOperationId === $operation->getOperationId()) {
                return (float)$total;
            }
            $total += $operation->getAmount();
        }

        return (float)$total;
    }

    private function createMoneySubtractOperation(
        int $currentOperationId,
        MoneyTransferIntention $moneyTransferIntention
    ): Operation
    {
        $operation = new Operation(
            $currentOperationId,
            $moneyTransferIntention->getPayerAccount()->getUid(),
            - 1 * $moneyTransferIntention->getAmount()
        );
        $payerAccountBalance = $this->getAccountBalance($moneyTransferIntention->getPayerAccount()->getUid());
        $amountToBeSubtracted = $moneyTransferIntention->getAmount();

        if(empty($this->operationsList) || $payerAccountBalance > $amountToBeSubtracted ) {
            return $operation;
        }

        throw InvalidOperationException::payerAccountHasInsufficientBalance();
    }
}
