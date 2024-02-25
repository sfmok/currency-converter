<?php

declare(strict_types=1);

namespace Tests\Sfmok\Currency\Unit\Encoder;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Sfmok\Currency\Encoder\JsonEncoder;
use Sfmok\Currency\Exception\NotEncodableValueException;

class JsonEncoderTest extends TestCase
{
    #[DataProvider('jsonDataProvider')]
    public function testEncode(array $data, string $expectedResult): void
    {
        self::assertSame($expectedResult, (new JsonEncoder())->encode($data));
    }

    public function testEncodeWithException(): void
    {
        self::expectException(NotEncodableValueException::class);
        /* @psalm-suppress InvalidArgument */
        (new JsonEncoder())->encode(["\xB1\x31"]);
    }

    /** @psalm-api  */
    public static function jsonDataProvider(): iterable
    {
        return [
            [['USD' => 1.25, 'EUR' => 1.1, 'GBP' => 1.4], '{"USD":1.25,"EUR":1.1,"GBP":1.4}'],
            [[], '[]'],
            [
                ['USD' => ['EUR' => 0.8, 'GBP' => 0.7], 'EUR' => ['USD' => 1.25, 'GBP' => 0.88]],
                '{"USD":{"EUR":0.8,"GBP":0.7},"EUR":{"USD":1.25,"GBP":0.88}}',
            ],
        ];
    }
}
