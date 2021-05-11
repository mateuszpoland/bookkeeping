<?php
declare(strict_types=1);

namespace CasinoWallet\ValueObject;

class WalletCoin implements CoinInterface
{
    public function getExpirationDate(): ?\DateTime
    {
        return null;
    }
}
