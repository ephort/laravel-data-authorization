<?php

namespace Ephort\LaravelDataAuthorization;

use Illuminate\Database\Eloquent\Model;
use Spatie\LaravelData\DataPipes\DataPipe;
use Spatie\LaravelData\Lazy;
use Spatie\LaravelData\Support\Creation\CreationContext;
use Spatie\LaravelData\Support\DataClass;

final class ResolveAuthorizationsPipe implements DataPipe
{
    public function __construct(
        private readonly AuthorizationArrayResolver $resolver,
    ) {
    }

    public function handle(mixed $payload, DataClass $class, array $properties, CreationContext $creationContext): array
    {
        if (! $payload instanceof Model) {
            return $properties;
        }

        $dataClass = $creationContext->dataClass;

        if (! is_subclass_of($dataClass, DataWithAuthorization::class)) {
            return $properties;
        }

        return [
            ...$properties,
            'authorization' => Lazy::create(fn () => $this->resolver->resolve($payload, $dataClass))->defaultIncluded(),
        ];
    }
}
