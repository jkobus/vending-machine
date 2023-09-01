<?php

namespace App\Tests\VendingMachine;

use App\VendingMachine\Credit;
use PHPUnit\Framework\TestCase;

class CreditTest extends TestCase
{
    public function testGetAmount()
    {
        $credit = new Credit();
        $credit->add(100);
        $this->assertEquals(100, $credit->getAmount());
    }

    public function testHasEnoughCredit()
    {
        $credit = new Credit();
        $credit->add(100);
        $this->assertTrue($credit->hasEnoughCredit(100));
        $this->assertFalse($credit->hasEnoughCredit(101));
    }

    public function testAddCredit()
    {
        $credit = new Credit();
        $credit->add(100);
        $this->assertEquals(100, $credit->getAmount());
    }

    public function testSubtractCredit()
    {
        $credit = new Credit();
        $credit->add(100);
        $credit->subtract(50);
        $this->assertEquals(50, $credit->getAmount());
    }

    public function testReset()
    {
        $credit = new Credit();
        $credit->add(100);
        $credit->reset();
        $this->assertEquals(0, $credit->getAmount());
    }
}
