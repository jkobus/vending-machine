<?php

declare(strict_types=1);

namespace App\VendingMachine;

class Credit
{
    private int $amount = 0;

    public function add(int $amount): void
    {
        $this->amount += $amount;
    }

    public function subtract(int $amount): void
    {
        $this->amount -= $amount;
    }

    public function hasEnoughCredit(int $amount): bool
    {
        return $this->amount >= $amount;
    }

    public function getAmount(): int
    {
        return $this->amount;
    }

    public function reset(): void
    {
        $this->amount = 0;
    }
}
