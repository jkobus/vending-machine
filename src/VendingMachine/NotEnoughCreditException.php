<?php

namespace App\VendingMachine;

class NotEnoughCreditException extends \LogicException implements VendingMachineException
{
    public function __construct()
    {
        parent::__construct('Not enough credit');
    }
}