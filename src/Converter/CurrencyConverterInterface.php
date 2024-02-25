<?php

declare(strict_types=1);

namespace Sfmok\Currency\Converter;

use Brick\Money\Money;

interface CurrencyConverterInterface
{
    public function convert(Money $money): string;
}
