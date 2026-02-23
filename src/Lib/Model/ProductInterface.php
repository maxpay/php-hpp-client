<?php

declare(strict_types=1);

namespace Maxpay\Lib\Model;

interface ProductInterface
{
    public function toHashMap(): array;
}
