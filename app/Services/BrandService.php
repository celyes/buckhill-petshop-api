<?php

namespace App\Services;

use App\Models\Brand;

class BrandService
{
    public function listBrands(array $data)
    {
        $per_page = $data['perPage'] ?? 10;
        $orderBy = $data['orderBy'] ?? 'id';
        $limit = $data['limit'] ?? null;
        $desc = $data['desc'] ?? false;

        return Brand::orderBy($orderBy, $desc ? 'desc' : 'asc')
            ->limit($limit)
            ->paginate($per_page)
            ->toArray();
    }
}
