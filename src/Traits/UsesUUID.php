<?php

namespace Fligno\BoilerplateGenerator\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * Trait UsesUUID
 *
 * @author James Carlo Luchavez <jamescarlo.luchavez@fligno.com>
 * @since 2021-11-11
 */
trait UsesUUID
{

    /**
     * Generates a UUID during model creation.
     */
    public static function bootUsesUuid(): void
    {
        static::creating(function (Model $model) {
            $model->uuid = Str::uuid();
        });
    }
}
