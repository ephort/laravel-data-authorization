<?php

namespace Ephort\LaravelDataAuthorization\Tests\Fixtures;

use Spatie\LaravelData\Data;

class TestDataWithoutAuthorization extends Data
{
    public function __construct(
        public int $id,
        public string $text,
    ) {
    }
}
