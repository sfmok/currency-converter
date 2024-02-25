<?php

declare(strict_types=1);

namespace Sfmok\Currency\Decoder;

final class JsonDecoder implements DecoderInterface
{
    public function decode(string $data): ?array
    {
        $value = json_decode($data, true);
        if (json_last_error()) {
            return null;
        }

        return $value;
    }
}
