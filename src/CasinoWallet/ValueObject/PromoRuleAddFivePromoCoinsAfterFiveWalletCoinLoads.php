<?php
declare(strict_types=1);

namespace CasinoWallet\ValueObject;

use CasinoWallet\Event\CoinAddedToWalletEvent;
use CasinoWallet\Event\WalletEvent;

class PromoRuleAddFivePromoCoinsAfterFiveWalletCoinLoads implements PromoRuleInterface
{
    private int $walletCoinsAddedCount;

    public function __construct()
    {
        $this->walletCoinsAddedCount = 0;
    }

    public function onEvent(array $coins, WalletEvent $event): array
    {
        if(!$this->supports($event)) {
            return $coins;
        }

        $this->walletCoinsAddedCount ++;

        if($this->walletCoinsAddedCount === 5) {
            $this->walletCoinsAddedCount = 0;
            return  $this->applyPromotionToWallet($coins);
        }

        return $coins;
    }

    private function applyPromotionToWallet(array $coins): array
    {
        $extraCoinCounter = 0;
        while($extraCoinCounter < 5) {
            $coins[] = new PromoCoin(5);
            $extraCoinCounter ++;
        }

        return $coins;
    }

    private function supports(WalletEvent $walletEvent): bool
    {
        return $walletEvent instanceof CoinAddedToWalletEvent && $walletEvent->getCoin() instanceof WalletCoin;
    }
}
