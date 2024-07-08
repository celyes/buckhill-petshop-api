<?php

namespace App\Filters;

use App\Models\Brand;
use Illuminate\Database\Eloquent\Builder;

/*
|--------------------------------------------------------------------------
| Filter class
|--------------------------------------------------------------------------
|
| Filter classes are an elegant way to filter a set of Eloquent models based
| on the values sent in the query parameter. The functions you provide in
| your filter class are always bound to a specific query parameter.
|
*/

class ProductFilter extends Filter
{
    /**
     * @param mixed $value
     * @return Builder
     */
    public function title(mixed $value): Builder
    {
        return $this->builder->where('title', 'like', '%' . $value . '%');
    }

    /**
     * @param mixed $value
     * @return Builder
     */
    public function price(mixed $value): Builder
    {
        return $this->builder->whereBetween('price', [(float)$value - 10, (float)$value + 10]);
    }

    public function brand(mixed $value): Builder
    {
        $brand = Brand::where('title', 'like', '%' . $value)->first();
        if (is_null($brand)) {
            return $this->builder;
        }
        return $this->builder->whereJsonContains('metadata->brand', $brand?->uuid);
    }
}
