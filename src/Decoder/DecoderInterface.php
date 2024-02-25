<?php

declare(strict_types=1);

namespace Sfmok\Currency\Decoder;

interface DecoderInterface
{
    public function decode(string $data): ?array;
}
