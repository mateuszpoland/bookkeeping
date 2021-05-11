<?php
declare(strict_types=1);

namespace CasinoWallet\Event;

use CasinoWallet\ValueObject\CoinInterface;

class CoinAddedToWalletEvent implements WalletEvent
{
    private CoinInterface $coin;

    public function __construct(CoinInterface $coin)
    {
        $this->coin = $coin;
    }

    public function getCoin(): CoinInterface
    {
        return $this->coin;
    }
}
