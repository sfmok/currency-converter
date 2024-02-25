<?php

declare(strict_types=1);

namespace Sfmok\Currency\Converter;

use Brick\Math\RoundingMode;
use Brick\Money\Money;
use Sfmok\Currency\DataProvider\ExchangeRateProviderInterface;
use Sfmok\Currency\Encoder\EncoderInterface;
use Sfmok\Currency\Exception\InvalidAmountException;
use Sfmok\Currency\Exception\InvalidCurrencyException;

final readonly class CurrencyConverter implements CurrencyConverterInterface
{
    public function __construct(private ExchangeRateProviderInterface $exchangeRateProvider, private EncoderInterface $encoder)
    {
    }

    public function convert(Money $money): string
    {
        if ($money->getAmount()->isNegative()) {
            throw new InvalidAmountException('Invalid amount: Amount must be a positive number.');
        }

        $exchangeRates = $this->exchangeRateProvider->getExchangeRates();

        // Checking existing currency code in exchange rates
        if (null === $rates = $exchangeRates[$money->getCurrency()->getCurrencyCode()] ?? null) {
            throw new InvalidCurrencyException('Invalid currency code: Currency code not found in exchange rates.');
        }

        $results = [];
        /**
         * @var string           $currencyCode
         * @var int|float|string $rate
         */
        foreach ($rates as $currencyCode => $rate) {
            $results[$currencyCode] = (string) $money->multipliedBy(
                Money::of($rate, $currencyCode)->getAmount(),
                RoundingMode::UP,
            )->getAmount();
        }

        return $this->encoder->encode($results);
    }
}
