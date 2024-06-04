<?php

namespace Ephort\LaravelDataAuthorization\Tests\Fixtures;

use Illuminate\Foundation\Auth\User as Authenticatable;

class UserModel extends Authenticatable
{
    protected $table = 'user_models';

    protected $guarded = [];

    public $timestamps = false;
}
