<?php

namespace Ephort\LaravelDataAuthorization;

use Ephort\LaravelDataAuthorization\Contracts\DataAuthorizationContract;
use Illuminate\Database\Eloquent\Model;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataPipeline;
use Spatie\LaravelData\Lazy;

abstract class DataWithAuthorization extends Data implements DataAuthorizationContract
{
    public Lazy|array $authorization;

    public static function pipeline(): DataPipeline
    {
        return parent::pipeline()->firstThrough(ResolveAuthorizationsPipe::class);
    }

    public function withoutAuthorization(): static
    {
        return $this->excludePermanently('authorization');
    }

    final protected static function resolveAuthorizationArray(Model $model): array
    {
        return resolve(AuthorizationArrayResolver::class)->resolve($model, static::class);
    }
}
