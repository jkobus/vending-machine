<?php

namespace App;

use App\VendingMachine\NotEnoughCoinsException;
use App\VendingMachine\NotEnoughCreditException;
use App\VendingMachine\ProductIsOutOfStockException;
use App\VendingMachine\RuntimeException;

interface VendingMachineInterface
{
    /**
     * Insert coin to vending machine.
     */
    public function insertCoin(Coin $coin): void;

    /**
     * All exceptions thrown by this method must implement VendingMachineException.
     *
     * @see VendingMachineException
     * @throws ProductIsOutOfStockException When product is out of stock
     * @throws NotEnoughCreditException When there is not enough credit to purchase the product
     * @throws NotEnoughCoinsException When there is not enough coins to give change
     * @throws RuntimeException In case of any other unexpected error
     */
    public function selectAndPurchaseProduct(string $productId): void;

    /**
     * If product was successfully bought, it will be available in pickup box.
     */
    public function getProductFromPickupBox(): ?ProductInterface;

    /**
     * After the purchase, you can pick up your change from here.
     * If coin was not accepted, it will be also here.
     *
     * @return array<Coin>
     */
    public function getCoinsFromCoinReturn(): array;
}