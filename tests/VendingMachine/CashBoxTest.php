<?php

declare(strict_types=1);

namespace App\Tests\VendingMachine;

use App\Coin;
use App\VendingMachine\CashBox;
use LogicException;
use PHPUnit\Framework\TestCase;

/**
 * @covers \App\VendingMachine\CashBox
 */
class CashBoxTest extends TestCase
{
    public function test_add_coin(): void
    {
        $cashbox = new CashBox();
        $cashbox->addCoin(Coin::of(50));

        $this->addToAssertionCount(1);
    }

    public function test_get_change(): void
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

    /**
     * @covers \App\VendingMachine\NotEnoughCoinsException
     */
    public function test_get_change_when_there_are_no_coins_in_the_box_throws_exception(): void
    {
        $this->expectException(LogicException::class);
        $this->expectExceptionMessage('Not enough coins');
        $cashbox = new CashBox();
        $cashbox->getChange(100);
    }
}
