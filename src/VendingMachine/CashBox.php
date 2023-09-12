<?php

declare(strict_types=1);

namespace App\VendingMachine;

use App\Coin;

class CashBox
{
    /**
     * @var array<int, array<Coin>>|array{}
     */
    private array $trays = [];

    /**
     * @param array<Coin> $coins
     */
    public function __construct(array $coins = [])
    {
        foreach ($coins as $coin) {
            $this->addCoin($coin);
        }
    }

    public function addCoin(Coin $coin): void
    {
        $this->trays[$coin->value()][] = $coin;
    }

    /**
     * @return array<Coin>
     */
    public function getChange(int $amount): array
    {
        krsort($this->trays, \SORT_NUMERIC);
        $trays = array_keys($this->trays);
        $pickedCoins = [];

        while ($amount > 0) {
            $coin = null;
            foreach ($trays as $value) {
                // amount needed is smaller than the current coin value
                if ($amount < $value) {
                    continue;
                }

                $coin = $this->pickCoinFromTray($value);

                if ($coin === null) {
                    continue;
                }

                $pickedCoins[] = $coin;
                $amount -= $value;

                break;
            }

            // we were not able to pick any coins to fulfill the change request
            if ($coin === null) {
                throw new NotEnoughCoinsException();
            }
        }

        return $pickedCoins;
    }

    private function pickCoinFromTray(int $coinValue): ?Coin
    {
        if (!isset($this->trays[$coinValue]) || empty($this->trays[$coinValue])) {
            return null;
        }

        return array_shift($this->trays[$coinValue]);
    }
}
