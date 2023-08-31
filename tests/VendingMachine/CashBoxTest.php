<?php

namespace App\Tests\VendingMachine;

use App\Coin;
use App\VendingMachine\CashBox;
use PHPUnit\Framework\TestCase;

class CashBoxTest extends TestCase
{
    public function testAddCoin()
    {
        $cashbox = new CashBox();
        $cashbox->addCoin(Coin::of(50));

        $this->addToAssertionCount(1);
    }

    public function testGetChange()
    {
        $cashbox = new CashBox([
            Coin::of(5),
            Coin::of(20),
            Coin::of(10),
            Coin::of(10),
            Coin::of(20),
            Coin::of(50),
            Coin::of(5),
            Coin::of(1),
            Coin::of(1),
            Coin::of(1),
            Coin::of(1),
            Coin::of(1),
        ]);

        $change = $cashbox->getChange(50);
        $expectedChange = [Coin::of(50)];

        $this->assertEquals($expectedChange, $change);

        $change = $cashbox->getChange(50);
        $expectedChange = [Coin::of(20), Coin::of(20), Coin::of(10)];

        $this->assertEquals($expectedChange, $change);
    }

    public function testGetChangeWhenThereAreNoCoinsInTheBoxThrowsException()
    {
        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage('Not enough coins');
        $cashbox = new CashBox();
        $cashbox->getChange(100);
    }
}
