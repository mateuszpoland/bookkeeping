<?php
declare(strict_types=1);

namespace CasinoWallet\ValueObject;


use CasinoWallet\Event\CoinRemovedFromWalletEvent;
use CasinoWallet\Event\WalletEvent;

class PromoRuleAddTenPromoCoinsAfterTenSubtractions implements PromoRuleInterface
{
    private int $coinSubtractionCount;

    public function __construct()
    {
        $this->coinSubtractionCount = 0;
    }
    public function onEvent(array $walletCoins, WalletEvent $walletEvent): array
    {
        if(!$this->supports($walletEvent)) {
            return $walletCoins;
        }

        $this->coinSubtractionCount ++;

        if($this->coinSubtractionCount === 10) {
            $this->coinSubtractionCount = 0;
            return  $this->applyPromotionToWallet($walletCoins);
        }

        return $walletCoins;
    }

    private function applyPromotionToWallet(array $coins): array
    {
        $extraCoinCounter = 0;
        while($extraCoinCounter < 10) {
            $coins[] = new PromoCoin(10);
            $extraCoinCounter ++;
        }

        return $coins;
    }

    private function supports(WalletEvent $walletEvent): bool
    {
        // tylko odjęcie WalletCoina liczy się do reguły promocji
        return $walletEvent instanceof CoinRemovedFromWalletEvent && $walletEvent->getCoin() instanceof WalletCoin;
    }
}
