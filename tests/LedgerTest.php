<?php

namespace Tpay\Tests;

use PHPUnit\Framework\TestCase;
use Tpay\Domain\Exception\InvalidOperationException;
use Tpay\Domain\MoneyTransferIntention;
use Tpay\Service\Ledger;
use Tpay\Domain\Account;

class LedgerTest extends TestCase
{
    private Account $account1;
    private Account $account2;
    private Account $account3;
    private Account $account4;

    public function setUp(): void
    {
        $this->account1 = new Account( 'Płatnicy');
        $this->account2 = new Account( 'Tpay');
        $this->account3 = new Account( 'Sprzedawca');
        $this->account4 = new Account( 'Bank');
    }

    public function testOperationMoveMoneyBetweenAccounts(): void
    {
        $ledger = $this->getLedger();

        $ledger->transferMoney(new MoneyTransferIntention($this->account1, $this->account2, 100));
        $ledger->transferMoney(new MoneyTransferIntention($this->account2, $this->account3, 99));
        $ledger->transferMoney(new MoneyTransferIntention($this->account2, $this->account4, 0.5));

        $this->assertEquals(-100, $ledger->getAccountBalance($this->account1->getUid()));
        $this->assertEquals(0.5, $ledger->getAccountBalance($this->account2->getUid()));
        $this->assertEquals(99, $ledger->getAccountBalance($this->account3->getUid()));
        $this->assertEquals(0.5, $ledger->getAccountBalance($this->account4->getUid()));

        // assert that balance is 0 after each operation
        $this->assertEquals((float)0, $ledger->getTotalOperationBalance());
        $this->assertEquals((float)0, $ledger->getTotalOperationBalance(0));
        $this->assertEquals((float)0, $ledger->getTotalOperationBalance(1));
        $this->assertEquals((float)0, $ledger->getTotalOperationBalance(2));
        $this->assertEquals((float)0, $ledger->getTotalOperationBalance(3));

        // check account balances after specified operations
        $this->assertEquals(-100, $ledger->getAccountBalanceForOperationId($this->account1->getUid(), 2));
        $this->assertEquals(1, $ledger->getAccountBalanceForOperationId($this->account2->getUid(), 1));
    }

    public function testShouldRaiseErrorOnAttemptToSubtractMoreMoneyThanIsOnAccount(): void
    {
        $ledger = $this->getLedger();

        $this->expectExceptionMessage(InvalidOperationException::payerAccountHasInsufficientBalance()->getMessage());

        $ledger->transferMoney(new MoneyTransferIntention($this->account1, $this->account2, 100));
        $ledger->transferMoney(new MoneyTransferIntention($this->account2, $this->account3, 120));
    }

    public function getLedger(): Ledger
    {
        return new Ledger();
    }
}
