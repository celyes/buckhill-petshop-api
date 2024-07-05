<?php

namespace App\Models;

use App\Traits\HasSlug;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    use HasFactory, HasUuid, HasSlug;

    protected $fillable = [
        'title'
    ];

    protected static function boot()
    {
        parent::boot();
        static::setUuid();
        static::setSlugs();
    }

    public function getRouteKeyName(): string
    {
        return 'uuid';
    }
}
