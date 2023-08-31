<?php

namespace App;

use App\VendingMachine\CashBox;
use App\VendingMachine\CoinAcceptor;
use App\VendingMachine\Credit;
use App\VendingMachine\Tray;
use LogicException;
use RuntimeException;

class VendingMachine implements VendingMachineInterface
{
    private Credit $credit;
    private CashBox $cashBox;
    private CoinAcceptor $coinAcceptor;
    private array $trays = [];

    /**
     * @var array<ProductInterface>
     */
    private array $pickupBox = [];
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
        // @todo rather than throwing exception if coin is unknown lets just drop it in the coin return?
        $this->coinAcceptor->accept($coin);
        $this->cashBox->addCoin($coin);
        $this->credit->addCredit($coin->value());
    }

    public function selectAndPurchaseProduct(string $productId): void
    {
        $tray = $this->findTrayWithProductId($productId);

        if(!$tray->hasProductInStock()) {
            throw new LogicException('Product is out of stock');
        }

        $product = $tray->getProduct();

        if(!$this->credit->hasEnoughCredit($product->getPrice())) {
            throw new LogicException('Not enough credit');
        }

        $this->credit->subtractCredit($product->getPrice());
        $this->dropCoinsInCoinReturn($this->cashBox->getChange($this->credit->getAmount()));
        $this->credit->reset();

        $tray->decreaseQuantity();
        $this->dropProductInPickupBox($product);
    }

    private function findTrayWithProductId(string $productId): Tray
    {
        foreach ($this->trays as $tray) {
            if ($tray->hasProduct($productId)) {
                return $tray;
            }
        }
        throw new RuntimeException('Product not found in any of the trays');
    }

    private function dropCoinsInCoinReturn(array $coins): void
    {
        $this->coinReturn = array_merge($this->coinReturn, $coins);
    }

    private function dropProductInPickupBox(ProductInterface $product): void
    {
        $this->pickupBox[] = $product;
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
}