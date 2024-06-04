<?php

namespace Ephort\LaravelDataAuthorization;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Gate;

final class AuthorizationArrayResolver
{
    public function resolve(Model $model, string $dataClass): array
    {
        $authorizations = (array) $dataClass::getAuthorizations();

        return collect($authorizations)
            ->mapWithKeys(function (string $action) use ($model) {
                return [$action => Gate::allows($action, $model)];
            })
            ->toArray();
    }
}
