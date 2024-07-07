<?php

namespace App\Pagination;

use Illuminate\Pagination\LengthAwarePaginator;

class SimplePaginator extends LengthAwarePaginator
{
    public function toArray(): array
    {
        return [
            'current_page' => $this->currentPage(),
            'data' => $this->items->toArray(),
            'first_page_url' => $this->url(1),
            'from' => $this->firstItem(),
            'last_page' => $this->lastPage(),
            'last_page_url' => $this->url($this->lastPage()),
            'links' => [
                [
                    'url' => $this->previousPageUrl(),
                    'label' => '&laquo; Previous',
                    'active' => false,
                ],
                [
                    'url' => $this->url($this->currentPage()),
                    'label' => '&laquo; Previous',
                    'active' => true,
                ],
                [
                    'url' => $this->nextPageUrl(),
                    'label' => '&raquo; Next',
                    'active' => false,
                ],
            ],
            'next_page_url' => $this->nextPageUrl(),
            'path' => $this->path(),
            'per_page' => $this->perPage(),
            'prev_page_url' => $this->previousPageUrl(),
            'to' => $this->lastItem(),
            'total' => $this->total(),
        ];
    }
}
