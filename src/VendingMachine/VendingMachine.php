<?php

declare(strict_types=1);

namespace App\VendingMachine;

use App\Coin;
use App\ProductInterface;
use App\VendingMachineInterface;

class VendingMachine implements VendingMachineInterface
{
    private Credit $credit;
    private CashBox $cashBox;
    private CoinAcceptor $coinAcceptor;

    /**
     * @var array<Tray>
     */
    private array $trays = [];

    /**
     * @var array<ProductInterface>
     */
    private array $pickupBox = [];

    /**
     * @var array<Coin>
     */
    private array $coinReturn = [];

    /**
     * @param array<Tray> $trays
     */
    public function __construct(CashBox $cashBox = new CashBox(), array $trays = [], CoinAcceptor $coinAcceptor = new CoinAcceptor())
    {
        $this->coinAcceptor = $coinAcceptor;
        $this->cashBox = $cashBox;
        $this->trays = $trays;
        $this->credit = new Credit();
    }

    public function insertCoin(Coin $coin): void
    {
        if ($this->coinAcceptor->isAccepted($coin)) {
            $this->cashBox->addCoin($coin);
            $this->credit->add($coin->value());
        } else {
            $this->dropCoinsInCoinReturn([$coin]);
        }
    }

    public function selectAndPurchaseProduct(string $productId): void
    {
        $tray = $this->findTrayWithProductId($productId);

        if (!$tray->hasProductInStock()) {
            throw new ProductIsOutOfStockException($tray->getProduct());
        }

        $product = $tray->getProduct();

        if (!$this->credit->hasEnoughCredit($product->getPrice())) {
            throw new NotEnoughCreditException();
        }

        $this->credit->subtract($product->getPrice());
        $this->dropProductInPickupBox($tray);

        $this->dropCoinsInCoinReturn($this->cashBox->getChange($this->credit->getAmount()));
        $this->credit->reset();
    }

    public function getProductFromPickupBox(): ?ProductInterface
    {
        return array_pop($this->pickupBox);
    }

    /**
     * @return array<Coin>
     */
    public function getCoinsFromCoinReturn(): array
    {
        return $this->coinReturn;
    }

    public function cancel(): void
    {
        $this->dropCoinsInCoinReturn($this->cashBox->getChange($this->credit->getAmount()));
        $this->credit->reset();
    }

    private function findTrayWithProductId(string $productId): Tray
    {
        foreach ($this->trays as $tray) {
            if ($tray->hasProduct($productId)) {
                return $tray;
            }
        }

        // this is probably an inconsistency with potential frontend, that shows products that are not in the machine
        throw new RuntimeException('Product not found in any of the trays, probably bad product id');
    }

    private function dropCoinsInCoinReturn(array $coins): void
    {
        $this->coinReturn = array_merge($this->coinReturn, $coins);
    }

    private function dropProductInPickupBox(Tray $tray): void
    {
        $tray->decreaseQuantity();
        $this->pickupBox[] = $tray->getProduct();
    }
}
