<?php

declare(strict_types=1);

use Brick\Money\Money;
use Sfmok\Currency\Converter\CurrencyConverter;
use Sfmok\Currency\DataProvider\ExchangeRateProvider;
use Sfmok\Currency\Decoder\JsonDecoder;
use Sfmok\Currency\Encoder\JsonEncoder;

require __DIR__.'/../vendor/autoload.php';

// Example usage
$data = '[
    {
        "baseCurrency": "EUR",
        "exchangeRates": {
            "EUR": 1,
            "USD": 1.22,
            "CHF": 1.08,
            "CNY": 7.75
        }
    },
    {
        "baseCurrency": "USD",
        "exchangeRates": {
            "EUR": 0.82,
            "USD": 1,
            "CHF": 0.89,
            "CNY": 6.39
        }
    }
]';

try {
    $dataProvider = new ExchangeRateProvider($data, [new JsonDecoder()]);

    $encoder = new JsonEncoder();
    $converter = new CurrencyConverter($dataProvider, $encoder);

    echo $converter->convert(Money::of('0.99', 'USD'))."\n";
} catch (Throwable $e) {
    echo 'Error: '.$e->getMessage();
}
