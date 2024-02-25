<?php

declare(strict_types=1);

namespace Tests\Sfmok\Currency\Functional;

use Brick\Money\Money;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Sfmok\Currency\Converter\CurrencyConverter;
use Sfmok\Currency\DataProvider\ExchangeRateProvider;
use Sfmok\Currency\Decoder\JsonDecoder;
use Sfmok\Currency\Encoder\CsvEncoder;
use Sfmok\Currency\Encoder\EncoderInterface;
use Sfmok\Currency\Encoder\JsonEncoder;

class CurrencyConverterTest extends TestCase
{
    #[DataProvider('convertDataProvider')]
    public function testConvert(
        string $data,
        string|int|float $amount,
        string $currencyCode,
        EncoderInterface $encoder,
        string $expectedOutput,
    ): void {
        $converter = new CurrencyConverter(new ExchangeRateProvider($data, [new JsonDecoder()]), $encoder);
        $output = $converter->convert(Money::of($amount, $currencyCode));
        self::assertSame($expectedOutput, $output);
    }

    /** @psalm-api  */
    public static function convertDataProvider(): iterable
    {
        return [
            [
                '[{"baseCurrency": "USD", "exchangeRates": {"USD": 1.0, "EUR": 0.88, "GBP": "0.75"}}]',
                10,
                'USD',
                new JsonEncoder(),
                '{"USD":"10.00","EUR":"8.80","GBP":"7.50"}',
            ],
            [
                '[{"baseCurrency": "USD", "exchangeRates": {"USD": 1.0, "EUR": 0.88, "GBP": "0.75"}}]',
                '8.00',
                'USD',
                new JsonEncoder(),
                '{"USD":"8.00","EUR":"7.04","GBP":"6.00"}',
            ],
            [
                '[{"baseCurrency": "USD", "exchangeRates": {"USD": 1.00, "EUR": "0.5", "GBP": 0.44}}]',
                0.2,
                'USD',
                new CsvEncoder(),
                "USD,0.20\nEUR,0.10\nGBP,0.09\n",
            ],
            [
                '[{"baseCurrency": "USD", "exchangeRates": {"USD": 1.00, "EUR": "0.5", "GBP": 0.44}}]',
                '0.3',
                'USD',
                new CsvEncoder(),
                "USD,0.30\nEUR,0.15\nGBP,0.14\n",
            ],
        ];
    }
}
