<?php

namespace Ephort\LaravelDataAuthorization\Tests\Fixtures;

use Ephort\LaravelDataAuthorization\DataWithAuthorization;
use Spatie\LaravelData\Lazy;

class TestDataCustomFromMethod extends DataWithAuthorization
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

    public static function fromTestModel(TestModel $testModel): self
    {
        return self::from([
            'id' => $testModel->id,
            'text' => $testModel->text,
            'authorization' => Lazy::create(fn () => static::resolveAuthorizationArray($testModel))->defaultIncluded(),
        ]);
    }
}
