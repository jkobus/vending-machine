<?php

declare(strict_types=1);

namespace App\Tests\VendingMachine;

use App\VendingMachine\Credit;
use PHPUnit\Framework\TestCase;

/**
 * @covers \App\VendingMachine\Credit
 */
class CreditTest extends TestCase
{
    public function test_get_amount(): void
    {
        $credit = new Credit();
        $credit->add(100);
        $this->assertEquals(100, $credit->getAmount());
    }

    public function test_has_enough_credit(): void
    {
        $credit = new Credit();
        $credit->add(100);
        $this->assertTrue($credit->hasEnoughCredit(100));
        $this->assertFalse($credit->hasEnoughCredit(101));
    }

    public function test_add_credit(): void
    {
        $credit = new Credit();
        $credit->add(100);
        $this->assertEquals(100, $credit->getAmount());
    }

    public function test_subtract_credit(): void
    {
        $credit = new Credit();
        $credit->add(100);
        $credit->subtract(50);
        $this->assertEquals(50, $credit->getAmount());
    }

    public function test_reset(): void
    {
        $credit = new Credit();
        $credit->add(100);
        $credit->reset();
        $this->assertEquals(0, $credit->getAmount());
    }
}
