<?php

namespace App\VendingMachine;

/**
 * Transaction can be continued after this exception, but the change will not be returned.
 */
class NotEnoughCoinsException extends \LogicException implements VendingMachineException
{
    public function __construct()
    {
        parent::__construct('Not enough coins');
    }
}