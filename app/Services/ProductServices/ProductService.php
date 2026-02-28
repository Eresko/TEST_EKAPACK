<?php
declare(strict_types=1);

namespace App\Services\ProductServices;

use Carbon\Carbon;
use Illuminate\Support\Arr;
use App\Repository\ProductRepository;
use App\Dto\Product\ProductIndexDto;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Сервис для пробуктов
 */
class ProductService
{

    public function __construct(
        private ProductRepository         $productRepository,

    )
    {
    }

    public function getByFilter(ProductIndexDto $dto):LengthAwarePaginator {
        $collection = $this->productRepository->getByFilterAndSearch($dto);
        
        $perPage = $dto->getPerPage() ?? 10; // по умолчанию 10
        $page = $dto->getPage() ?? 1;
        
        $skip = ($page - 1) * $perPage;
        
        $itemsForPage = $collection->slice($skip, $perPage)->values();
        
        return new LengthAwarePaginator(
            $itemsForPage,
            $collection->count(),
            $perPage,
            $page,
            ['path' => request()->url(), 'query' => request()->query()]
        );
    }

}