<?php
declare(strict_types=1);

namespace CasinoWallet;

use CasinoWallet\Event\CoinAddedToWalletEvent;
use CasinoWallet\Event\CoinRemovedFromWalletEvent;
use CasinoWallet\ValueObject\CoinInterface;
use CasinoWallet\ValueObject\PromoRuleInterface;
use CasinoWallet\Event\WalletEvent;

class Wallet
{
    private array $coins;
    private array $promoRules;

    public function __construct(array $promoRules = [])
    {
        $this->promoRules = $promoRules;
    }

    public function addPromoRule(PromoRuleInterface $promoRule): void
    {
        $this->promoRules[] = $promoRule;
    }

    public function loadCoin(CoinInterface $coin): void
    {
        $this->coins[] = $coin;
        $this->notifyPromoProcessors(new CoinAddedToWalletEvent($coin));
    }

    public function unloadCoin(): void
    {
        $coin = array_shift($this->coins);
        $this->notifyPromoProcessors(new CoinRemovedFromWalletEvent($coin));
    }

    public function getTotalCoins(): int
    {
        return count($this->coins);
    }

    private function notifyPromoProcessors(WalletEvent $event): void
    {
        $coins = $this->coins;
        foreach ($this->promoRules as $promoRule) {
            $coins = $promoRule->onEvent($coins, $event);
        }

        $this->coins = $coins;
    }
}
