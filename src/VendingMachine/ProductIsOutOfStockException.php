<?php

namespace App\VendingMachine;

use App\ProductInterface;

/**
 * Transaction can be continued after this exception, just select different product
 */
class ProductIsOutOfStockException extends \LogicException implements VendingMachineException
{
    public function __construct(private readonly ProductInterface $product)
    {
        parent::__construct('Product is out of stock');
    }

    public function getProduct(): ProductInterface
    {
        return $this->product;
    }
}