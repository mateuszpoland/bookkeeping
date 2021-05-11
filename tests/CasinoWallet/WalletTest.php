<?php
declare(strict_types=1);

namespace Tests\CasinoWallet;

use CasinoWallet\ValueObject\PromoRuleAddTenPromoCoinsAfterTenSubtractions;
use PHPUnit\Framework\TestCase;
use CasinoWallet\Wallet;
use CasinoWallet\ValueObject\WalletCoin;
use CasinoWallet\ValueObject\PromoRuleAddFivePromoCoinsAfterFiveWalletCoinLoads;

class WalletTest extends TestCase
{

    public function testAddOneWalletCoin(): void
    {
        $wallet = new Wallet();
        $wallet->loadCoin(new WalletCoin());

        self::assertEquals(1, $wallet->getTotalCoins());
    }

    /*
     * Test rule 1: Dodanie 5 PromoCoin ważnych 5 dni, po 5 doładowaniach portfela za pomocą WalletCoin
     */
    public function testAddTenPromoCoinsWithExpiryDateTenDaysAfterTenCoinsHadBeenRemoved(): Wallet
    {
        $wallet = new Wallet();
        $wallet->addPromoRule(new PromoRuleAddFivePromoCoinsAfterFiveWalletCoinLoads());

        $wallet->loadCoin(new WalletCoin());
        $wallet->loadCoin(new WalletCoin());
        $wallet->loadCoin(new WalletCoin());
        $wallet->unloadCoin();
        $wallet->loadCoin(new WalletCoin());

        self::assertEquals(3, $wallet->getTotalCoins());

        $wallet->loadCoin(new WalletCoin());

        self::assertEquals(9, $wallet->getTotalCoins());

        return $wallet;
    }

    /**
     * @depends testAddTenPromoCoinsWithExpiryDateTenDaysAfterTenCoinsHadBeenRemoved
     * @param Wallet $wallet
     */
    public function testAddTenPromoCoinsForTenDaysAfterTenCoinSubtractions(Wallet $wallet): void
    {
        $wallet->addPromoRule(new PromoRuleAddTenPromoCoinsAfterTenSubtractions());

        for($i = 0; $i < 6; $i++) {
            $wallet->loadCoin(new WalletCoin());
        }

        self::assertEquals(20, $wallet->getTotalCoins());

        for($i = 0; $i < 20; $i++) {
            $wallet->unloadCoin();
        }

        # po 20 odjęciach powyżej, zużyjemy 10 WalletCoinów, co dopiero odpali promocję i doładuje nam konto 10 PormoCoinami
        self::assertEquals(10, $wallet->getTotalCoins());
    }
}
