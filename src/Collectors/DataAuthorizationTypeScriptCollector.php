<?php

namespace Ephort\LaravelDataAuthorization\Collectors;

use Ephort\LaravelDataAuthorization\DataWithAuthorization;
use Ephort\LaravelDataAuthorization\Transformers\DataAuthorizationTypeScriptTransformer;
use ReflectionClass;
use Spatie\TypeScriptTransformer\Collectors\Collector;
use Spatie\TypeScriptTransformer\Structures\TransformedType;

class DataAuthorizationTypeScriptCollector extends Collector
{
    public function getTransformedType(ReflectionClass $class): ?TransformedType
    {
        if (! $class->isSubclassOf(DataWithAuthorization::class)) {
            return null;
        }

        $transformer = new DataAuthorizationTypeScriptTransformer($this->config);

        return $transformer->transform($class, $class->getShortName());
    }
}
