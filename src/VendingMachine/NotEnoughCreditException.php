<?php

namespace App\VendingMachine;

/**
 * Transaction can be continued after this exception, just add more coins
 */
class NotEnoughCreditException extends \LogicException implements VendingMachineException
{
    public function __construct()
    {
        parent::__construct('Not enough credit');
    }
}