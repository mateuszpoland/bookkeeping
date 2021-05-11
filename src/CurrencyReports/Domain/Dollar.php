<?php
declare(strict_types=1);

namespace CashReporter\Domain;

class Dollar
{
    private int $amount;

    public function __construct(int $amount)
    {
        $this->amount = $amount;
    }

    public function times(int $multiplier): Dollar
    {
        return new Dollar($this->amount * $multiplier);
    }

    public function amount(): int
    {
        return $this->amount;
    }

    public function equals(Dollar $dollar): bool
    {
        return $dollar->amount() === $this->amount;
    }
}
