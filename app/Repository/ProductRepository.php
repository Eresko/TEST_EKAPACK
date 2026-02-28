<?php
declare(strict_types=1);

namespace App\Repository;

use Carbon\Carbon;
use App\Models\Product;
use App\Dto\Product\ProductIndexDto;
use Illuminate\Support\Collection;

/**
 * Сервис для пробуктов
 */
class ProductRepository
{


    /**
     * @param ProductIndexDto $dto
     * @return Collection
     */
    public function getByFilterAndSearch(ProductIndexDto $dto):Collection
    {
        $query = Product::query();
        
        if ($dto->getCategory()) {
            $query->where('category', $dto->getCategory());
        }
        
        if ($dto->getSearch()) {
            $search = $dto->getSearch();
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                    ->orWhere('sku', 'like', "%$search%");
            });
        }

        return $query->get();
    }

    /**
     * @param int $productId
     * @param int $quantity
     * @return bool
     */
    public function decreaseStock(int $productId, int $quantity): bool
    {
        $product = Product::find($productId);
        if ($product && $product->stock >= $quantity) {
            $product->decrement('stock', $quantity);
            return true;
        }
        return false;
    }

    /**
     * @param int $productId
     * @return Product|null
     */
    public function getProductById(int $productId): ?Product
    {
        return Product::find($productId);
    }

}