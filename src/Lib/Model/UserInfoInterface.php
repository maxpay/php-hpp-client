<?php

declare(strict_types=1);

namespace Maxpay\Lib\Model;

interface UserInfoInterface
{
    public function toHashMap(): array;
}
