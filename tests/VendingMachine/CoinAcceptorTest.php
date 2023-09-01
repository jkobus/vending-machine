<?php

namespace App\Tests\VendingMachine;

use App\Coin;
use App\VendingMachine\CoinAcceptor;
use PHPUnit\Framework\TestCase;

class CoinAcceptorTest extends TestCase
{
    public function testIsAccepted()
    {
        $coinAcceptor = new CoinAcceptor();
        $coin = Coin::of(1);

        $this->assertTrue($coinAcceptor->isAccepted($coin));

        $coin = Coin::of(100);
        $this->assertFalse($coinAcceptor->isAccepted($coin));
    }
}
