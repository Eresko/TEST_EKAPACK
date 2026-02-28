<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Requests\ProductIndexRequest;
use App\Services\ProductServices\ProductService;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use OpenApi\Attributes as OA;

class ProductController extends Controller
{
    public function __construct(private ProductService $productService)
    {
    }

    #[OA\Get(
        path: "/api/products",
        tags: ["Товары"],
        summary: "Получение списка товаров с фильтрацией и пагинацией",
        operationId: "getProducts",
        parameters: [
            new OA\Parameter(
                name: "category",
                in: "query",
                required: false,
                description: "Фильтр по категории",
                schema: new OA\Schema(type: "string", example: "electronics")
            ),
            new OA\Parameter(
                name: "search",
                in: "query",
                required: false,
                description: "Поиск по названию товара",
                schema: new OA\Schema(type: "string", example: "iPhone")
            ),
            new OA\Parameter(
                name: "perPage",
                in: "query",
                required: false,
                description: "Количество элементов на странице",
                schema: new OA\Schema(type: "integer", minimum: 1, example: 10)
            ),
            new OA\Parameter(
                name: "page",
                in: "query",
                required: false,
                description: "Номер страницы",
                schema: new OA\Schema(type: "integer", minimum: 1, example: 1)
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Список товаров с пагинацией",
                content: new OA\JsonContent(
                    type: "object",
                    properties: [
                        new OA\Property(
                            property: "data",
                            type: "array",
                            items: new OA\Items(
                                type: "object", 
                                properties: [
                                    new OA\Property(property: "id", type: "integer", example: 1),
                                    new OA\Property(property: "name", type: "string", example: "iPhone 13"),
                                    new OA\Property(property: "price", type: "number", format: "float", example: 999.99),
                                    new OA\Property(property: "category", type: "string", example: "electronics"),
                                ]
                            )
                        ),
                        new OA\Property(
                            property: "pagination",
                            type: "object",
                            properties: [
                                new OA\Property(property: "total", type: "integer", example: 100),
                                new OA\Property(property: "per_page", type: "integer", example: 10),
                                new OA\Property(property: "current_page", type: "integer", example: 1),
                                new OA\Property(property: "last_page", type: "integer", example: 10),
                            ]
                        )
                    ]
                )
            ),
            new OA\Response(
                response: 422,
                description: "Ошибка валидации параметров"
            )
        ]
    )]
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