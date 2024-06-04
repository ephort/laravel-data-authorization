<?php

namespace Ephort\LaravelDataAuthorization\Tests\Fixtures;

use Ephort\LaravelDataAuthorization\ResolveAuthorizationsPipe;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataPipeline;

class TestDataWithCustomPipeline extends Data
{
    public function __construct(
        public int $id,
        public string $text,
    ) {
    }

    public static function pipeline(): DataPipeline
    {
        return parent::pipeline()->firstThrough(ResolveAuthorizationsPipe::class);
    }
}
