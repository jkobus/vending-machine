<?php

namespace App;

interface ProductInterface
{
    public function getId(): string;

    public function getName(): string;
}