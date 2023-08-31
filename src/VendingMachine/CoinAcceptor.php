<?php

namespace App\VendingMachine;

use App\Coin;

class CoinAcceptor
{
    private static array $accepted = [1, 2, 5, 10, 20, 50];

    public function __construct()
    {
    }

    /**
     * @throws \LogicException
     */
    public function accept(Coin $coin): void
    {
        if(!in_array($coin->value(), self::$accepted, true)) {
            throw new \LogicException('Invalid coin value');
        }
    }
}