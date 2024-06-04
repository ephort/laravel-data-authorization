<?php

use Ephort\LaravelDataAuthorization\Collectors\DataAuthorizationTypeScriptCollector;
use Ephort\LaravelDataAuthorization\Tests\Fixtures\TestData;
use Ephort\LaravelDataAuthorization\Tests\Fixtures\TestDataWithoutAuthorization;
use Ephort\LaravelDataAuthorization\Transformers\DataAuthorizationTypeScriptTransformer;
use Spatie\TypeScriptTransformer\TypeScriptTransformerConfig;

it('can collect DataWithAuthorization', function () {
    $collector = new DataAuthorizationTypeScriptCollector(
        TypeScriptTransformerConfig::create()->transformers([
            DataAuthorizationTypeScriptTransformer::class,
        ])
    );

    $reflection = new ReflectionClass(TestData::class);
    $transformedType = $collector->getTransformedType($reflection);

    expect($transformedType->transformed)->toMatchSnapshot()
        ->and($transformedType->transformed)->toContain('authorization: { view: boolean; update: boolean; }');
});

it('detects if data object is not DataWithAuthorization', function () {
    $collector = new DataAuthorizationTypeScriptCollector(
        TypeScriptTransformerConfig::create()->transformers([
            DataAuthorizationTypeScriptTransformer::class,
        ])
    );

    $reflection = new ReflectionClass(TestDataWithoutAuthorization::class);
    $transformedType = $collector->getTransformedType($reflection);

    expect($transformedType)->toBeNull();
});
