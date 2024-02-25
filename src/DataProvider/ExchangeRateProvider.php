<?php

declare(strict_types=1);

namespace Sfmok\Currency\DataProvider;

use Sfmok\Currency\Decoder\DecoderInterface;

final readonly class ExchangeRateProvider implements ExchangeRateProviderInterface
{
    /** @param array<DecoderInterface> $decoders */
    public function __construct(public string $data, public array $decoders)
    {
    }

    public function getExchangeRates(): array
    {
        $exchangeRates = $this->parseExchangeRates();

        $result = [];
        foreach ($exchangeRates as $rate) {
            $currencyCode = $rate[self::BASE_CURRENCY_ATTR] ?? null;
            $currencyExchangeRate = $rate[self::EXCHANGE_RATES_ATTR] ?? null;
            if (null === $currencyCode || null === $currencyExchangeRate) {
                // Should we skip instead of throw an exception?
                throw new \RuntimeException(sprintf(
                    "Invalid JSON data format: '%s' and/or '%s' key(s) not found.",
                    self::BASE_CURRENCY_ATTR,
                    self::EXCHANGE_RATES_ATTR,
                ));
            }

            \assert(\is_string($currencyCode));
            \assert(\is_array($currencyExchangeRate));

            $result[$currencyCode] = $currencyExchangeRate;
        }

        return $result;
    }

    private function parseExchangeRates(): array
    {
        foreach ($this->decoders as $decoder) {
            if (null !== $exchangeRates = $decoder->decode($this->data)) {
                return $exchangeRates;
            }
        }
        throw new \RuntimeException('Failed to parse exchange rates data.');
    }
}
