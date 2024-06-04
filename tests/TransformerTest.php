<?php

use Ephort\LaravelDataAuthorization\Tests\Fixtures\TestData;
use Ephort\LaravelDataAuthorization\Tests\Fixtures\TestDataWithEmptyAuthorizations;
use Ephort\LaravelDataAuthorization\Transformers\DataAuthorizationTypeScriptTransformer;
use Spatie\TypeScriptTransformer\TypeScriptTransformerConfig;

beforeEach(function () {
    $this->transformer = new DataAuthorizationTypeScriptTransformer(
        resolve(TypeScriptTransformerConfig::class)
    );
});

it('can transform a data object with authorization', function () {
    $type = $this->transformer->transform(
        new ReflectionClass(TestData::class),
        'TestData'
    );

    expect($type->transformed)->toMatchSnapshot()
        ->and($type->transformed)->toContain('authorization: { view: boolean; update: boolean; }');
});

it('does not add authorization if data object authorizations is empty', function () {
    $type = $this->transformer->transform(
        new ReflectionClass(TestDataWithEmptyAuthorizations::class),
        'TestData'
    );

    expect($type->transformed)->toMatchSnapshot()
        ->and($type->transformed)->not()->toContain('authorization');
});
