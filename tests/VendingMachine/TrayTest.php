<?php

declare(strict_types=1);

namespace App\Tests\VendingMachine;

use App\VendingMachine\Product;
use App\VendingMachine\Tray;
use PHPUnit\Framework\TestCase;

/**
 * @covers \App\VendingMachine\Tray
 */
class TrayTest extends TestCase
{
    public function test_decrease_quantity(): void
    {
        $product = new Product('A', 'Juice', 100);
        $tray = new Tray($product, 1);

        $this->assertTrue($tray->hasProductInStock());
        $tray->decreaseQuantity();
        $this->assertFalse($tray->hasProductInStock());
    }

    public function test_has_product_in_stock(): void
    {
        $product = new Product('A', 'Juice', 100);
        $tray = new Tray($product, 1);

        $this->assertTrue($tray->hasProductInStock());

        $tray = new Tray($product, 0);
        $this->assertFalse($tray->hasProductInStock());
    }

    public function test_has_product(): void
    {
        $product = new Product('A', 'Juice', 100);
        $tray = new Tray($product, 1);
        $this->assertTrue($tray->hasProduct('A'));
        $this->assertFalse($tray->hasProduct('B'));
    }

    public function test_get_product(): void
    {
        $product = new Product('A', 'Juice', 100);
        $tray = new Tray($product, 1);

        $this->assertEquals($product, $tray->getProduct());

        $tray = new Tray($product, 0);
        $this->assertEquals($product, $tray->getProduct());
    }
}
