<?php

namespace App\VendingMachine;

use App\Coin;

class CashBox
{
    private array $trays = [];

    /**
     * @param array<Coin> $coins
     */
    public function __construct(array $coins = [])
    {
        // each tray holds coins of the same value
        $this->trays = [
            50 => [],
            20 => [],
            10 => [],
            5 => [],
            2 => [],
            1 => [],
        ];

        foreach ($coins as $coin) {
            $this->addCoin($coin);
        }
    }

    public function addCoin(Coin $coin): void
    {
        if(!isset($this->trays[$coin->value()])) {
            throw new \LogicException(sprintf('This CashBox does not support that coin: %s', $coin->value()));
        }

        $this->trays[$coin->value()][] = $coin;
    }

    public function getChange(int $amount): array
    {
        $trays = array_keys($this->trays);
        $pickedCoins = [];

        while($amount > 0) {
            $coin = null;
            foreach ($trays as $value) {

                // amount needed is smaller than the current coin value
                if($amount < $value) {
                    continue;
                }

                $coin = $this->pickCoinFromTray($value);

                if($coin === null) {
                    continue;
                }

                $pickedCoins[] = $coin;
                $amount -= $value;
                break;
            }

            // we were not able to pick any coins to fulfill the change request
            if($coin === null) {
                throw new \LogicException('Not enough coins');
            }
        }

        return $pickedCoins;
    }

    private function pickCoinFromTray(int $coinValue): ?Coin
    {
        if(!isset($this->trays[$coinValue]) || empty($this->trays[$coinValue])) {
            return null;
        }

        return array_shift($this->trays[$coinValue]);
    }
}