<?php

declare(strict_types=1);

namespace App;

use InvalidArgumentException;

final class Coin
{
    /**
     * @var array|int[]
     */
    private static array $allowed = [1, 2, 5, 10, 20, 50, 100];

    private int $value;

    public function __construct(int $value)
    {
        if (!in_array($value, self::$allowed, true)) {
            throw new InvalidArgumentException(sprintf('Invalid coin value: %s', $value));
        }

        $this->value = $value;
    }

    public function value(): int
    {
        return $this->value;
    }

    public static function of(int $value): self
    {
        return new self($value);
    }
}
