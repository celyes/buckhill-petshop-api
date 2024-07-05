<?php

namespace App\Traits\Models;

use Illuminate\Support\Str;

trait HasUuid
{
    protected static function setUuid(): void
    {
        static::creating(function ($model) {
            $model->uuid = (string) Str::uuid();
        });
    }
}
