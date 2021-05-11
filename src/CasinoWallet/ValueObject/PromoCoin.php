<?php
declare(strict_types=1);

namespace CasinoWallet\ValueObject;

class PromoCoin implements CoinInterface
{
    private \DateTime $expirationDate;

    public function __construct(int $daysToExpiration)
    {
        $this->expirationDate = (new \DateTime())->modify(sprintf('+ %s day', (string)$daysToExpiration));
    }

    public function getExpirationDate(): ?\DateTime
    {
        return $this->expirationDate;
    }
}
