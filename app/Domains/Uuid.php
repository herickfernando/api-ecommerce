<?php

namespace App\Domains;

use Uuid as BaseUuid;

trait Uuid
{
    /**
     * Boot function from laravel.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->{$model->getKeyName()} = $model->{$model->getKeyName()} ?: BaseUuid::generate()->string;
        });
    }
}
