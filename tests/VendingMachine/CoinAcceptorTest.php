<?php

declare(strict_types=1);

namespace App\Tests\VendingMachine;

use App\Coin;
use App\VendingMachine\CoinAcceptor;
use PHPUnit\Framework\TestCase;

/**
 * @covers \App\VendingMachine\CoinAcceptor
 */
class CoinAcceptorTest extends TestCase
{
    /**
     * @return array<array{Coin, boolean}>
     */
    public static function data(): array
    {
        return [
            [Coin::of(1), true],
            [Coin::of(2), true],
            [Coin::of(5), true],
            [Coin::of(10), true],
            [Coin::of(20), true],
            [Coin::of(50), true],
            [Coin::of(100), false],
        ];
    }

    /**
     * @dataProvider data
     */
    public function test_is_accepted(Coin $coin, bool $isAccepted): void
    {
        $coinAcceptor = new CoinAcceptor();
        $this->assertSame($isAccepted, $coinAcceptor->isAccepted($coin));
    }
}
