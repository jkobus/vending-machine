<?php

namespace App;

interface VendingMachineInterface
{
    public function insertCoin(Coin $coin): void;
    public function selectAndPurchaseProduct(string $productId): void;
    public function getProductFromPickupBox(): ?ProductInterface;

    /**
     * @return array<Coin>
     */
    public function getCoinsFromCoinReturn(): array;
}