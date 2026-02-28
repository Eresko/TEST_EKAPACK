<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Requests\ProductIndexRequest;
use App\Services\ProductServices\ProductService;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
class ProductController extends Controller
{

    /**
     * @OA\Tag(
     *     name="Products",
     *     description="API для продуктов"
     * )
     */
    public function __construct(private ProductService $productService)
    {
    }


    /**
     *
     * 
     * @OA\Get(
     *     path="/api/products",
     *     tags={"Товары"},
     *     summary="Получение списка товаров с фильтрацией и пагинацией",
     *
     *     @OA\Parameter(
     *         name="category",
     *         in="query",
     *         required=false,
     *         description="Фильтр по категории",
     *         @OA\Schema(type="string", example="electronics")
     *     ),
     *
     *     @OA\Parameter(
     *         name="search",
     *         in="query",
     *         required=false,
     *         description="Поиск по названию товара",
     *         @OA\Schema(type="string", example="iPhone")
     *     ),
     *
     *     @OA\Parameter(
     *         name="perPage",
     *         in="query",
     *         required=false,
     *         description="Количество элементов на странице",
     *         @OA\Schema(type="integer", minimum=1, example=10)
     *     ),
     *
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         required=false,
     *         description="Номер страницы",
     *         @OA\Schema(type="integer", minimum=1, example=1)
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Список товаров с пагинацией",
     *         @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/ProductResource")
     *             ),
     *
     *             @OA\Property(
     *                 property="pagination",
     *                 type="object",
     *                 @OA\Property(property="total", type="integer", example=100),
     *                 @OA\Property(property="per_page", type="integer", example=10),
     *                 @OA\Property(property="current_page", type="integer", example=1),
     *                 @OA\Property(property="last_page", type="integer", example=10)
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=422,
     *         description="Ошибка валидации параметров"
     *     )
     * )
     */
    public function index(ProductIndexRequest $request): JsonResponse
    {
        $dto = $request->toDto();
        $productsPage = $this->productService->getByFilter($dto);

        return response()->json([
            'data' => ProductResource::collection($productsPage->items()),
            'pagination' => [
                'total' => $productsPage->total(),
                'per_page' => $productsPage->perPage(),
                'current_page' => $productsPage->currentPage(),
                'last_page' => $productsPage->lastPage(),
            ],
        ]);
    }

}
