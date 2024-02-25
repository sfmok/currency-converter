<?php

declare(strict_types=1);

namespace Sfmok\Currency\Encoder;

interface EncoderInterface
{
    public function encode(array $data): string;
}
