<?php

namespace App\Traits\Models;

use Illuminate\Support\Str;

trait HasSlug
{
    protected static string $slugKey = 'title';
    protected static string $slugColumn = 'slug';

    protected static function setSlugs(): void
    {
        static::creating(function ($model) {
            $model->{static::$slugColumn} = Str::slug($model->{static::$slugKey});
        });
    }
}
