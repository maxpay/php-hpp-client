<?php

declare(strict_types=1);

namespace Maxpay\Lib\Util;

use Maxpay\Lib\Exception\GeneralMaxpayException;

interface ClientInterface
{
    /**
     * @param array $data
     * @return array
     * @throws GeneralMaxpayException
     */
    public function send(array $data): array;
}
