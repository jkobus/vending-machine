<?php

namespace App\Tests\VendingMachine;

use App\VendingMachine\Product;
use PHPUnit\Framework\TestCase;

class ProductTest extends TestCase
{

    public function testGetName()
    {
        $product = new Product('1', 'Coca Cola', 100);
        $this->assertEquals('Coca Cola', $product->getName());
    }

    public function testGetId()
    {
        $product = new Product('1', 'Coca Cola', 100);
        $this->assertEquals('1', $product->getId());
    }

    public function testGetPrice()
    {
        $product = new Product('1', 'Coca Cola', 100);
        $this->assertEquals(100, $product->getPrice());
    }
}
