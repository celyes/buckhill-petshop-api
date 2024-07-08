<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;

abstract class Filter
{
    protected function __construct(protected Builder $builder, protected array $params)
    {
    }

    public static function apply(Builder $builder, array $params): self
    {
        return new static($builder, $params);
    }

    public function get()
    {
        foreach ($this->params as $key => $value) {
            try {
                $this->builder = $this->$key($value);
            } catch(\Error) {
                // Fail silently
            }
        }
        return $this->builder;
    }
}
