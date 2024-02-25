<?php

declare(strict_types=1);

namespace Tests\Sfmok\Currency\Unit\Decoder;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Sfmok\Currency\Decoder\JsonDecoder;

class JsonDecoderTest extends TestCase
{
    #[DataProvider('jsonDataProvider')]
    public function testDecode(string $jsonData, ?array $expectedResult): void
    {
        self::assertSame($expectedResult, (new JsonDecoder())->decode($jsonData));
    }

    #[DataProvider('jsonInvalidDataProvider')]
    public function testDecodeWithInvalidJsonData(string $jsonData): void
    {
        self::assertNull((new JsonDecoder())->decode($jsonData));
    }

    /** @psalm-api  */
    public static function jsonDataProvider(): iterable
    {
        return [
            ['{"USD": 1.25, "EUR": 1.1, "GBP": 1.4}', ['USD' => 1.25, 'EUR' => 1.1, 'GBP' => 1.4]],
            ['{"USD": 1.25, "EUR": 1.1, "GBP": 1.4', null],
            ['', null],
        ];
    }

    /** @psalm-api  */
    public static function jsonInvalidDataProvider(): iterable
    {
        return [
            ["{'foo': 'bar'}"],
            ['foobar'],
            ['invalid'],
        ];
    }
}
