<?php
declare(strict_types=1);

namespace CasinoWallet\Event;

use CasinoWallet\ValueObject\CoinInterface;

interface WalletEvent
{
    public function getCoin(): CoinInterface;
}
