<?php

declare(strict_types=1);

namespace Tests\Sfmok\Currency\Unit\Encoder;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Sfmok\Currency\Encoder\CsvEncoder;

class CsvEncoderTest extends TestCase
{
    #[DataProvider('encodeDataProvider')]
    public function testEncode(array $data, string $expectedResult): void
    {
        self::assertSame($expectedResult, (new CsvEncoder())->encode($data));
    }

    /** @psalm-api  */
    public static function encodeDataProvider(): iterable
    {
        return [
            [['USD' => 1.25, 'EUR' => 1.1, 'GBP' => 1.4], "USD,1.25\nEUR,1.1\nGBP,1.4\n"],
            [[], ''],
            [['USD' => 100, 'EUR' => 200, 'GBP' => 300], "USD,100\nEUR,200\nGBP,300\n"],
        ];
    }
}
