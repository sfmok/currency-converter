<?php

declare(strict_types=1);

namespace Sfmok\Currency\DataProvider;

interface ExchangeRateProviderInterface
{
    public const BASE_CURRENCY_ATTR = 'baseCurrency';
    public const EXCHANGE_RATES_ATTR = 'exchangeRates';

    public function getExchangeRates(): array;
}
