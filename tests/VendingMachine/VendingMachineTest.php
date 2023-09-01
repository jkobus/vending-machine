<?php

namespace App\Tests\VendingMachine;

use App\Coin;
use App\VendingMachine\VendingMachineFactory;
use PHPUnit\Framework\TestCase;

class VendingMachineTest extends TestCase
{
    public function testGetCoinsFromCoinReturn()
    {
        $vendingMachine = VendingMachineFactory::createWithDefaultStock();
        $vendingMachine->insertCoin(new Coin(50));
        $vendingMachine->insertCoin(new Coin(50));
        $vendingMachine->selectAndPurchaseProduct('A');
        $this->assertEquals([new Coin(5)], $vendingMachine->getCoinsFromCoinReturn());
    }

    public function testSelectAndPurchaseProduct()
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
     */
    public function testSelectAndPurchaseProductOneAfterAnotherIsNotPossible()
    {
        $this->expectException(\LogicException::class);
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
    public function testCoinsAreCollectedInTheCoinReturnWhenNotPickedUp()
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

    public function testSelectAndPurchaseProductWithoutEnoughMoneyInTheMachineThrowsException()
    {
        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage('Not enough credit');

        $vendingMachine = VendingMachineFactory::createWithDefaultStock();
        $vendingMachine->insertCoin(new Coin(50));
        $vendingMachine->selectAndPurchaseProduct('A');
    }

    public function testGetProductFromPickupBox()
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

    public function testGetProductFromPickupBoxWhenEmptyReturnsNull()
    {
        $vendingMachine = VendingMachineFactory::createWithDefaultStock();
        $product = $vendingMachine->getProductFromPickupBox();
        $this->assertNull($product);
    }

    public function testCoinThatIsNotAcceptedCanBePickedUpFromCoinReturn()
    {
        $vendingMachine = VendingMachineFactory::createWithDefaultStock();
        $vendingMachine->insertCoin(new Coin(100));

        $coins = $vendingMachine->getCoinsFromCoinReturn();
        $this->assertEquals([new Coin(100)], $coins);
    }
}
