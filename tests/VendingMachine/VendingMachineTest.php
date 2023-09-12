<?php

declare(strict_types=1);

namespace App\Tests\VendingMachine;

use App\Coin;
use App\VendingMachine\CashBox;
use App\VendingMachine\NotEnoughCoinsException;
use App\VendingMachine\NotEnoughCreditException;
use App\VendingMachine\Product;
use App\VendingMachine\ProductIsOutOfStockException;
use App\VendingMachine\RuntimeException;
use App\VendingMachine\Tray;
use App\VendingMachine\VendingMachine;
use App\VendingMachine\VendingMachineFactory;
use PHPUnit\Framework\TestCase;

/**
 * @covers \App\VendingMachine\VendingMachine
 */
class VendingMachineTest extends TestCase
{
    public function test_get_coins_from_coin_return(): void
    {
        $vendingMachine = VendingMachineFactory::createWithDefaultStock();
        $vendingMachine->insertCoin(new Coin(50));
        $vendingMachine->insertCoin(new Coin(50));
        $vendingMachine->selectAndPurchaseProduct('A');
        $this->assertEquals([new Coin(5)], $vendingMachine->getCoinsFromCoinReturn());
    }

    public function test_select_and_purchase_product(): void
    {
        $vendingMachine = VendingMachineFactory::createWithDefaultStock();
        $vendingMachine->insertCoin(new Coin(50));
        $vendingMachine->insertCoin(new Coin(50));
        $vendingMachine->selectAndPurchaseProduct('A');

        $product = $vendingMachine->getProductFromPickupBox();
        $this->assertNotNull($product);
        $this->assertEquals('A', $product->getId());
        $this->assertEquals('Juice', $product->getName());
        $this->assertEquals([new Coin(5)], $vendingMachine->getCoinsFromCoinReturn());
    }

    /**
     * After buying first product, all coins that are not needed for
     * change should be returned to coin return, therefore the remaining credit will be 0.
     *
     * @covers \App\VendingMachine\NotEnoughCreditException
     */
    public function test_select_and_purchase_product_one_after_another_is_not_possible(): void
    {
        $this->expectException(NotEnoughCreditException::class);
        $this->expectExceptionMessage('Not enough credit');

        $vendingMachine = VendingMachineFactory::createWithDefaultStock();
        $vendingMachine->insertCoin(new Coin(50));
        $vendingMachine->insertCoin(new Coin(50));
        $vendingMachine->insertCoin(new Coin(50));
        $vendingMachine->insertCoin(new Coin(50));
        $vendingMachine->selectAndPurchaseProduct('A');
        $vendingMachine->selectAndPurchaseProduct('A');
    }

    /**
     * After buying first product, all coins that are not needed for
     * change should be returned to coin return, therefore the remaining credit will be 0.
     */
    public function test_coins_are_collected_in_the_coin_return_when_not_picked_up(): void
    {
        $vendingMachine = VendingMachineFactory::createWithDefaultStock();
        $vendingMachine->insertCoin(new Coin(50));
        $vendingMachine->insertCoin(new Coin(50));
        $vendingMachine->selectAndPurchaseProduct('A');

        $vendingMachine->insertCoin(new Coin(50));
        $vendingMachine->insertCoin(new Coin(50));
        $vendingMachine->selectAndPurchaseProduct('A');

        $this->assertEquals([new Coin(5), new Coin(5)], $vendingMachine->getCoinsFromCoinReturn());
    }

    /**
     * @covers \App\VendingMachine\NotEnoughCreditException
     */
    public function test_select_and_purchase_product_without_enough_money_in_the_machine_throws_exception(): void
    {
        $this->expectException(NotEnoughCreditException::class);
        $this->expectExceptionMessage('Not enough credit');

        $vendingMachine = VendingMachineFactory::createWithDefaultStock();
        $vendingMachine->insertCoin(new Coin(50));
        $vendingMachine->selectAndPurchaseProduct('A');
    }

    /**
     * @covers \App\VendingMachine\ProductIsOutOfStockException
     */
    public function test_select_and_purchase_product_that_is_out_of_stock_throws_exception(): void
    {
        $this->expectException(ProductIsOutOfStockException::class);
        $this->expectExceptionMessage('Product is out of stock');

        $vendingMachine = new VendingMachine(
            cashBox: new CashBox([]),
            trays: [new Tray(new Product('A', 'Juice', 95), 0)]
        );

        $vendingMachine->insertCoin(new Coin(50));
        $vendingMachine->insertCoin(new Coin(50));
        $vendingMachine->selectAndPurchaseProduct('A');
    }

    public function test_select_and_purchase_product_despite_that_machine_is_unable_to_return_the_change(): void
    {
        $vendingMachine = new VendingMachine(
            cashBox: new CashBox([]),
            trays: [new Tray(new Product('A', 'Juice', 95), 1)]
        );

        $vendingMachine->insertCoin(new Coin(50));
        $vendingMachine->insertCoin(new Coin(50));

        try {
            $vendingMachine->selectAndPurchaseProduct('A');
        } catch (NotEnoughCoinsException) {
            // ignore
        }

        $product = $vendingMachine->getProductFromPickupBox();
        $this->assertNotNull($product);
    }

    public function test_get_product_from_pickup_box(): void
    {
        $vendingMachine = VendingMachineFactory::createWithDefaultStock();
        $vendingMachine->insertCoin(new Coin(50));
        $vendingMachine->insertCoin(new Coin(50));
        $vendingMachine->selectAndPurchaseProduct('A');

        $product = $vendingMachine->getProductFromPickupBox();
        $this->assertNotNull($product);
        $this->assertEquals('A', $product->getId());
        $this->assertEquals('Juice', $product->getName());
    }

    public function test_cancel_transaction(): void
    {
        $vendingMachine = VendingMachineFactory::createWithDefaultStock();
        $vendingMachine->insertCoin(new Coin(50));
        $vendingMachine->insertCoin(new Coin(50));
        $vendingMachine->cancel();

        $this->assertEquals([new Coin(50), new Coin(50)], $vendingMachine->getCoinsFromCoinReturn());
    }

    public function test_get_product_from_pickup_box_when_empty_returns_null(): void
    {
        $vendingMachine = VendingMachineFactory::createWithDefaultStock();
        $product = $vendingMachine->getProductFromPickupBox();
        $this->assertNull($product);
    }

    public function test_coin_that_is_not_accepted_can_be_picked_up_from_coin_return(): void
    {
        $vendingMachine = VendingMachineFactory::createWithDefaultStock();
        $vendingMachine->insertCoin(new Coin(100));

        $coins = $vendingMachine->getCoinsFromCoinReturn();
        $this->assertEquals([new Coin(100)], $coins);
    }

    /**
     * @covers \App\VendingMachine\RuntimeException
     */
    public function test_select_and_purchase_product_that_is_not_in_yhe_machine_throws_exception(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Product not found in any of the trays, probably bad product id');

        $vendingMachine = new VendingMachine(
            cashBox: new CashBox([]),
            trays: [new Tray(new Product('A', 'Juice', 95), 1)]
        );

        $vendingMachine->insertCoin(new Coin(50));
        $vendingMachine->insertCoin(new Coin(50));
        $vendingMachine->selectAndPurchaseProduct('XYZ');
    }
}
