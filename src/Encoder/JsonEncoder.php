<?php

declare(strict_types=1);

namespace Sfmok\Currency\Encoder;

use Sfmok\Currency\Exception\NotEncodableValueException;

final class JsonEncoder implements EncoderInterface
{
    public function encode(array $data): string
    {
        try {
            return json_encode($data, JSON_THROW_ON_ERROR);
        } catch (\JsonException $e) {
            throw new NotEncodableValueException($e->getMessage(), 0, $e);
        }
    }
}
