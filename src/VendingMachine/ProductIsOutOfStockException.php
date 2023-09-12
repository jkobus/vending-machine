<?php

declare(strict_types=1);

namespace App\VendingMachine;

use LogicException;

/**
 * Transaction can be continued after this exception, just select different product.
 */
class ProductIsOutOfStockException extends LogicException implements VendingMachineException
{
    public function __construct()
    {
        parent::__construct('Product is out of stock');
    }
}
