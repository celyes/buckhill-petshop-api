<?php

namespace App\Models;

use App\Filters\ProductFilter;
use App\Traits\Models\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Product extends Model
{
    use HasFactory, HasUuid;

    protected $fillable = [
        'uuid',
        'category_uuid',
        'title',
        'description',
        'price',
        'metadata'
    ];

    public function casts()
    {
        return [
            'metadata' => 'array',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
            'deleted_at' => 'datetime',
        ];
    }

    protected static function boot(): void
    {
        parent::boot();
        static::setUuid();
    }

    public function getRouteKeyName(): string
    {
        return 'uuid';
    }

    public function scopeFilter(Builder $builder, array $params)
    {
        return ProductFilter::apply($builder, $params)->get();
    }
}
