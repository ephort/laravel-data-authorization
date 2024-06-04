<?php

namespace Ephort\LaravelDataAuthorization\Contracts;

interface DataAuthorizationContract
{
    public static function getAuthorizations(): array;
}
