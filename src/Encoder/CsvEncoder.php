<?php

declare(strict_types=1);

namespace Sfmok\Currency\Encoder;

final class CsvEncoder implements EncoderInterface
{
    public function encode(array $data): string
    {
        $output = '';
        foreach ($data as $currency => $amount) {
            $output .= "{$currency},{$amount}\n";
        }

        return $output;
    }
}
