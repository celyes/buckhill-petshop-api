<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait HasUuid
{
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->uuid = (string) Str::uuid();
        });
    }
    public function getIncrementing(): bool
    {
        return false;
    }
    public function getKeyType(): string
    {
        return 'string';
    }
}
