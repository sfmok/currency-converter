<?php

declare(strict_types=1);

namespace Tests\Sfmok\Currency\Unit\Converter;

use Brick\Math\Exception\NumberFormatException;
use Brick\Math\Exception\RoundingNecessaryException;
use Brick\Math\RoundingMode;
use Brick\Money\Money;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Sfmok\Currency\Converter\CurrencyConverter;
use Sfmok\Currency\DataProvider\ExchangeRateProviderInterface;
use Sfmok\Currency\Encoder\EncoderInterface;
use Sfmok\Currency\Exception\InvalidAmountException;
use Sfmok\Currency\Exception\InvalidCurrencyException;

class CurrencyConverterTest extends TestCase
{
    private CurrencyConverter $currencyConverter;
    private ExchangeRateProviderInterface&MockObject $exchangeRateProvider;
    private EncoderInterface&MockObject $encoder;

    protected function setUp(): void
    {
        parent::setUp();
        $this->exchangeRateProvider = self::createMock(ExchangeRateProviderInterface::class);
        $this->encoder = self::createMock(EncoderInterface::class);
        $this->currencyConverter = new CurrencyConverter($this->exchangeRateProvider, $this->encoder);
    }

    public function testConvert(): void
    {
        $data = ['EUR' => '80.00', 'GBP' => '70.00'];
        $expectedResult = json_encode($data);
        $this->exchangeRateProvider->method('getExchangeRates')->willReturn(['USD' => ['EUR' => '0.8', 'GBP' => '0.7']]);
        $this->encoder->method('encode')->with($data)->willReturn($expectedResult);
        $result = $this->currencyConverter->convert(Money::of('100', 'USD'));
        self::assertSame($expectedResult, $result);
    }

    public function testConvertWithRounding(): void
    {
        $expectedResult = json_encode(['EUR' => '799.20', 'GBP' => '699.30']);
        $this->exchangeRateProvider->method('getExchangeRates')->willReturn(['USD' => ['EUR' => '0.8', 'GBP' => '0.7']]);
        $this->encoder->method('encode')->with(['EUR' => '799.20', 'GBP' => '699.30'])->willReturn($expectedResult);
        $result = $this->currencyConverter->convert(Money::of(998.997634, 'USD', roundingMode: RoundingMode::UP));
        self::assertSame($expectedResult, $result);
    }

    #[DataProvider('invalidAmountsDataProvider')]
    public function testConvertWithInvalidAmounts(string|float $amount, string $exception, string $message): void
    {
        /** @psalm-suppress ArgumentTypeCoercion */
        self::expectException($exception);
        self::expectExceptionMessage($message);
        $this->exchangeRateProvider->expects(self::never())->method('getExchangeRates');
        $this->encoder->expects(self::never())->method('encode');
        $this->currencyConverter->convert(Money::of($amount, 'USD'));
    }

    public function testConvertWithInvalidBaseCurrency(): void
    {
        self::expectException(InvalidCurrencyException::class);
        self::expectExceptionMessage('Invalid currency code: Currency code not found in exchange rates.');
        $this->exchangeRateProvider->method('getExchangeRates')->willReturn(['USD' => ['EUR' => 1.2]]);
        $this->encoder->expects(self::never())->method('encode');
        $this->currencyConverter->convert(Money::of('100', 'EUR'));
    }

    /** @psalm-api  */
    public static function invalidAmountsDataProvider(): iterable
    {
        return [
            ['-1000', InvalidAmountException::class, 'Invalid amount: Amount must be a positive number.'],
            ['1000+', NumberFormatException::class, 'The given value "1000+" does not represent a valid number.'],
            ['1000USD', NumberFormatException::class, 'The given value "1000USD" does not represent a valid number.'],
            ['$1000', NumberFormatException::class, 'The given value "$1000" does not represent a valid number.'],
            ['1,000.00', NumberFormatException::class, 'The given value "1,000.00" does not represent a valid number.'],
            ['1,000', NumberFormatException::class, 'The given value "1,000" does not represent a valid number.'],
            [999.997634, RoundingNecessaryException::class, 'Rounding is necessary to represent the result of the operation at this scale.'],
        ];
    }
}
