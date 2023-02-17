<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Pagination\LengthAwarePaginator;
use JetBrains\PhpStorm\ArrayShape;
use JetBrains\PhpStorm\Pure;
use Illuminate\Support\Collection;

/**
 * @property-read LengthAwarePaginator $resource
 */
class PaginationCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param Request $request
     * @return array
     */
    #[ArrayShape(['list' => Collection::class, 'paginate' => "array"])]
    #[Pure]
    public function toArray(Request $request): array
    {
        return [
            'list' => $this->resource->getCollection(),
            'paginate' => [
                "page" => $this->resource->currentPage(),
                "per_page" => $this->resource->perPage(),
                "last_page" => $this->resource->lastPage(),
                "total" => $this->resource->total(),
            ],
        ];
    }
}
