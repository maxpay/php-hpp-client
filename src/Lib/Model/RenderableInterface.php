<?php

declare(strict_types=1);

namespace Maxpay\Lib\Model;

interface RenderableInterface
{
    public function asString(): string;

    public function display(): void;
}
