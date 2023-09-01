<?php

namespace App\VendingMachine;

use App\Coin;
use App\VendingMachineInterface;

class VendingMachineFactory
{
    public static function createWithDefaultStock(): VendingMachineInterface
    {
        $coins = array_merge(
            self::getCoins(50, 5),
            self::getCoins(20, 5),
            self::getCoins(10, 5),
            self::getCoins(5, 5),
            self::getCoins(2, 5),
            self::getCoins(1, 10),
        );

        return new VendingMachine(
            cashBox: new CashBox($coins),
            trays: [
                new Tray(new Product('A', 'Juice', 95), 5),
                new Tray(new Product('B', 'Coffee', 233), 5),
                new Tray(new Product('C', 'Tea', 126), 5),

            ]
        );
    }

    public static function createEmpty(): VendingMachineInterface
    {
        return new VendingMachine(
            cashBox: new CashBox(),
            trays: []
        );
    }

    private static function getCoins(int $value, int $quantity): array
    {
        $coins = [];
        for ($i = 0; $i < $quantity; $i++) {
            $coins[] = new Coin($value);
        }
        return $coins;
    }
}