<?php
declare(strict_types=1);

namespace CasinoWallet\ValueObject;

interface CoinInterface
{
    public function getExpirationDate(): ?\DateTime;
}
