<?php

namespace App\VendingMachine;

use App\Coin;

class CoinAcceptor
{
    private static array $accepted = [1, 2, 5, 10, 20, 50];

    public function __construct()
    {
    }

    public function isAccepted(Coin $coin): bool
    {
        return in_array($coin->value(), self::$accepted, true);
    }
}