<?php

declare(strict_types=1);

namespace App\Tests;

use App\Coin;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

/**
 * @covers \App\Coin
 */
class CoinTest extends TestCase
{
    public function test_value(): void
    {
        $coin = new Coin(1);
        $this->assertEquals(1, $coin->value());
    }

    public function test_of(): void
    {
        $coin = Coin::of(1);
        $this->assertEquals(1, $coin->value());
    }

    public function test_that_using_invalid_value_throws_exception(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid coin value');
        Coin::of(111);
    }
}
