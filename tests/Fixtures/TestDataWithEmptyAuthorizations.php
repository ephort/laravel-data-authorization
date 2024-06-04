<?php

namespace Ephort\LaravelDataAuthorization\Tests\Fixtures;

use Ephort\LaravelDataAuthorization\DataWithAuthorization;

class TestDataWithEmptyAuthorizations extends DataWithAuthorization
{
    public function __construct(
        public int $id,
        public string $text,
    ) {
    }

    public static function getAuthorizations(): array
    {
        return [];
    }
}
