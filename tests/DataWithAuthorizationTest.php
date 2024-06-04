<?php

use Ephort\LaravelDataAuthorization\DataWithAuthorization as DataWithAuthorizationAlias;
use Ephort\LaravelDataAuthorization\Tests\Fixtures\TestData;
use Ephort\LaravelDataAuthorization\Tests\Fixtures\TestDataCustomFromMethod;
use Ephort\LaravelDataAuthorization\Tests\Fixtures\TestDataWithCustomPipeline;
use Ephort\LaravelDataAuthorization\Tests\Fixtures\TestDataWithoutAuthorization;
use Ephort\LaravelDataAuthorization\Tests\Fixtures\TestModel;
use Ephort\LaravelDataAuthorization\Tests\Fixtures\UserModel;

use function Pest\Laravel\actingAs;

it('adds the authorization array if the data object is DataWithAuthorization', function () {
    $testModel = TestModel::create([
        'text' => 'test',
    ]);

    $data = TestData::from($testModel)->toArray();

    expect($data)->toHaveKey('authorization');
});

it('does not add the authorization array if the data is not a model', function () {
    $data = TestData::from([
        'id' => 1,
        'text' => 'Test',
    ])->toArray();

    expect($data)->not()->toHaveKey('authorization');
});

it('does not add the authorization array if the data object is not DataWithAuthorization', function () {
    $testModel = TestModel::create([
        'text' => 'test',
    ]);

    $data = TestDataWithoutAuthorization::from($testModel)->toArray();

    expect($data)->not()->toHaveKey('authorization');
});

it('does not add the authorization array if the authorization is excluded', function (string $dataClass) {
    $testModel = TestModel::create([
        'text' => 'test',
    ]);

    /** @var DataWithAuthorizationAlias $dataClass */
    $data = $dataClass::from($testModel)->exclude('authorization')->toArray();

    expect($data)->not()->toHaveKey('authorization');
})->with([
    TestData::class,
    TestDataCustomFromMethod::class,
]);

it('does not add the authorization array if the authorization is excluded (helper method)', function (string $dataClass) {
    $testModel = TestModel::create([
        'text' => 'test',
    ]);

    /** @var DataWithAuthorizationAlias $dataClass */
    $data = $dataClass::from($testModel)->withoutAuthorization()->toArray();

    expect($data)->not()->toHaveKey('authorization');
})->with([
    TestData::class,
    TestDataCustomFromMethod::class,
]);

it('can authorize through the gate', function () {
    Gate::define('view', function (UserModel $user, TestModel $testModel) {
        return $user->id === $testModel->id;
    });

    $user = UserModel::create([
        'name' => 'Test User',
        'email' => 'test@example.com',
    ]);

    actingAs($user);

    $testModel = TestModel::create([
        'text' => 'test',
    ]);

    $data = TestData::from($testModel)->toArray();

    expect($data['authorization']['view'])->toBeTrue()
        ->and($data['authorization']['update'])->toBeFalse();
});

it('can authorize through the gate using a custom from method', function () {
    Gate::define('view', function (UserModel $user, TestModel $testModel) {
        return $user->id === $testModel->id;
    });

    $user = UserModel::create([
        'name' => 'Test User',
        'email' => 'test@example.com',
    ]);

    actingAs($user);

    $testModel = TestModel::create([
        'text' => 'test',
    ]);

    $data = TestDataCustomFromMethod::from($testModel)->toArray();

    expect($data['authorization']['view'])->toBeTrue()
        ->and($data['authorization']['update'])->toBeFalse();
});

it('does not add the authorization array if data object is not a DataWithAuthorization', function () {
    $testModel = TestModel::create([
        'text' => 'test',
    ]);

    $data = TestDataWithCustomPipeline::from($testModel)->toArray();

    expect($data)->not()->toHaveKey('authorization');
});
