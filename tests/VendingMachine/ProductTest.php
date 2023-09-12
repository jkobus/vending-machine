<?php

declare(strict_types=1);

namespace App\Tests\VendingMachine;

use App\VendingMachine\Product;
use PHPUnit\Framework\TestCase;

/**
 * @covers \App\VendingMachine\Product
 */
class ProductTest extends TestCase
{
    public function test_get_name(): void
    {
        $product = new Product('1', 'Coca Cola', 100);
        $this->assertEquals('Coca Cola', $product->getName());
    }

    public function test_get_id(): void
    {
        $product = new Product('1', 'Coca Cola', 100);
        $this->assertEquals('1', $product->getId());
    }

    public function test_get_price(): void
    {
        $product = new Product('1', 'Coca Cola', 100);
        $this->assertEquals(100, $product->getPrice());
    }
}
