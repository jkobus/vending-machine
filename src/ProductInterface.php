<?php

declare(strict_types=1);

namespace App;

interface ProductInterface
{
    public function getId(): string;

    public function getName(): string;
}
