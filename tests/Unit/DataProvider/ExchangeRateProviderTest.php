<?php

declare(strict_types=1);

namespace Tests\Sfmok\Currency\Unit\DataProvider;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Sfmok\Currency\DataProvider\ExchangeRateProvider;
use Sfmok\Currency\Decoder\JsonDecoder;

class ExchangeRateProviderTest extends TestCase
{
    #[DataProvider('validDataProvider')]
    public function testGetExchangeRatesWithValidData(string $data, array $expectedRates): void
    {
        $provider = new ExchangeRateProvider($data, [new JsonDecoder()]);
        self::assertSame($expectedRates, $provider->getExchangeRates());
    }

    /** @psalm-api  */
    public static function validDataProvider(): iterable
    {
        return [
            ['[{"baseCurrency": "USD", "exchangeRates": {"EUR": 1, "GBP": 2}}]', ['USD' => ['EUR' => 1, 'GBP' => 2]]],
            [
                '[{"baseCurrency": "USD", "exchangeRates": {"EUR": 0.8, "GBP": 0.7}}]',
                ['USD' => ['EUR' => 0.8, 'GBP' => 0.7]],
            ],
            [
                '[{"baseCurrency": "USD", "exchangeRates": {"EUR": "0.8", "GBP": "0.7"}}]',
                ['USD' => ['EUR' => '0.8', 'GBP' => '0.7']],
            ],
            [
                '[{"baseCurrency": "EUR", "exchangeRates": {"USD": 1.1, "GBP": 0.9}}, {"baseCurrency": "GBP", "exchangeRates": {"EUR": 1.1, "USD": 1.2}}]',
                ['EUR' => ['USD' => 1.1, 'GBP' => 0.9], 'GBP' => ['EUR' => 1.1, 'USD' => 1.2]],
            ],
        ];
    }

    #[DataProvider('invalidDataProvider')]
    public function testGetExchangeRatesWithInvalidData(string $data, string $exceptionMessage): void
    {
        self::expectException(\RuntimeException::class);
        self::expectExceptionMessage($exceptionMessage);
        $provider = new ExchangeRateProvider($data, [new JsonDecoder()]);
        $provider->getExchangeRates();
    }

    /** @psalm-api  */
    public static function invalidDataProvider(): iterable
    {
        return [
            ['invalid json', 'Failed to parse exchange rates data.'],
            ['{"baseCurrency":"USD"}', 'Invalid JSON data format: \'baseCurrency\' and/or \'exchangeRates\' key(s) not found.'],
            ['[{"exchangeRates":{"EUR":0.8,"GBP":0.7}}]', 'Invalid JSON data format: \'baseCurrency\' and/or \'exchangeRates\' key(s) not found.'],
        ];
    }
}
