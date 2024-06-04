<?php

namespace Ephort\LaravelDataAuthorization\Tests\Fixtures;

use Ephort\LaravelDataAuthorization\DataWithAuthorization;

class TestData extends DataWithAuthorization
{
    public function __construct(
        public int $id,
        public string $text,
    ) {
    }

    public static function getAuthorizations(): array
    {
        return [
            'view',
            'update',
        ];
    }
}
