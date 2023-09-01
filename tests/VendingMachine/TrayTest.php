<?php

namespace App\Tests\VendingMachine;

use App\VendingMachine\Product;
use App\VendingMachine\Tray;
use PHPUnit\Framework\TestCase;

class TrayTest extends TestCase
{
    public function testDecreaseQuantity()
    {
        $product = new Product('A', 'Juice', 100);
        $tray = new Tray($product, 1);

        $this->assertTrue($tray->hasProductInStock());
        $tray->decreaseQuantity();
        $this->assertFalse($tray->hasProductInStock());
    }

    public function testHasProductInStock()
    {
        $product = new Product('A', 'Juice', 100);
        $tray = new Tray($product, 1);

        $this->assertTrue($tray->hasProductInStock());

        $tray = new Tray($product, 0);
        $this->assertFalse($tray->hasProductInStock());
    }

    public function testHasProduct()
    {
        $product = new Product('A', 'Juice', 100);
        $tray = new Tray($product, 1);
        $this->assertTrue($tray->hasProduct('A'));
        $this->assertFalse($tray->hasProduct('B'));
    }

    public function testGetProduct()
    {
        $product = new Product('A', 'Juice', 100);
        $tray = new Tray($product, 1);

        $this->assertEquals($product, $tray->getProduct());
    }
}
