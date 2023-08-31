<?php

namespace App\VendingMachine;

use App\ProductInterface;

class Tray
{
    private ProductInterface $product;
    private int $quantity;

    public function __construct(ProductInterface $product, int $quantity)
    {
        $this->product = $product;
        $this->quantity = $quantity;
    }

    public function hasProduct(string $productId): bool
    {
        return $this->product->getId() === $productId;
    }

    public function hasProductInStock(): bool
    {
        return $this->quantity > 0;
    }

    public function getProduct(): ProductInterface
    {
        if($this->quantity === 0) {
            throw new \RuntimeException('Product is not available');
        }

        $this->quantity--;
        return $this->product;
    }

    public function decreaseQuantity(): void
    {
        $this->quantity--;
    }
}