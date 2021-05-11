<?php
declare(strict_types=1);

namespace CasinoWallet\ValueObject;

use CasinoWallet\Event\WalletEvent;

interface PromoRuleInterface
{
    public function onEvent(array $walletCoins, WalletEvent $walletEvent): array;
}
