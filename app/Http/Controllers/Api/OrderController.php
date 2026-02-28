<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Requests\OrderIndexRequest;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\OrderUpdateRequest;
use App\Services\OrderServices\OrderService; // Объявляем именно этот сервис
use Illuminate\Http\JsonResponse;
use App\Http\Resources\OrderResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use App\Http\Controllers\Controller;
use OpenApi\Attributes as OA;

class OrderController extends Controller
{



    public function __construct(private OrderService $orderService)
    {
        $this->middleware('rate.limit.create_orders')->only('store');
    }

    #[OA\Post(
        path: "/api/orders",
        tags: ["Заказы"],
        summary: "Создание нового заказа",
        operationId: "createOrder",
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["customer_id", "items"],
                properties: [
                    new OA\Property(
                        property: "customer_id",
                        type: "integer",
                        example: 1,
                        description: "ID клиента"
                    ),
                    new OA\Property(
                        property: "items",
                        type: "array",
                        minItems: 1,
                        items: new OA\Items(
                            type: "object",
                            required: ["product_id", "quantity"],
                            properties: [
                                new OA\Property(
                                    property: "product_id",
                                    type: "integer",
                                    example: 10,
                                    description: "ID товара"
                                ),
                                new OA\Property(
                                    property: "quantity",
                                    type: "integer",
                                    minimum: 1,
                                    example: 2,
                                    description: "Количество товара"
                                )
                            ]
                        )
                    )
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: "Заказ успешно создан",
                content: new OA\JsonContent(
                    type: "object",
                    properties: [
                        new OA\Property(
                            property: "order_id",
                            type: "integer",
                            example: 7
                        ),
                        new OA\Property(
                            property: "status",
                            type: "string",
                            example: "успешно создан"
                        )
                    ]
                )
            ),
            new OA\Response(
                response: 400,
                description: "Ошибка создания заказа",
                content: new OA\JsonContent(
                    type: "object",
                    properties: [
                        new OA\Property(
                            property: "status",
                            type: "string",
                            example: "error"
                        ),
                        new OA\Property(
                            property: "message",
                            type: "string",
                            example: "Недостаточно товаров для продукта iPhone"
                        )
                    ]
                )
            ),
            new OA\Response(
                response: 422,
                description: "Ошибка валидации"
            )
        ]
    )]
    public function store(StoreOrderRequest $request)
    {
        $dto = $request->toDto();

        $result = $this->orderService->createOrder($dto);

        return response()->json($result,201);
    }

    #[OA\Get(
        path: "/api/orders",
        tags: ["Заказы"],
        summary: "Получение списка заказов",
        operationId: "getOrders",
        parameters: [
            new OA\Parameter(
                name: "status",
                in: "query",
                description: "Статус заказа",
                required: false,
                schema: new OA\Schema(
                    type: "string",
                    enum: ["NEW", "CONFIRMED", "PROCESSING", "SHIPPED", "COMPLETED"]
                )
            ),
            new OA\Parameter(
                name: "customer_id",
                in: "query",
                description: "ID клиента",
                required: false,
                schema: new OA\Schema(
                    type: "integer"
                )
            ),
            new OA\Parameter(
                name: "date_from",
                in: "query",
                description: "Дата начала периода (YYYY-MM-DD)",
                required: false,
                schema: new OA\Schema(
                    type: "string",
                    format: "date"
                )
            ),
            new OA\Parameter(
                name: "date_to",
                in: "query",
                description: "Дата окончания периода (YYYY-MM-DD)",
                required: false,
                schema: new OA\Schema(
                    type: "string",
                    format: "date"
                )
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Список заказов",
                content: new OA\JsonContent(
                    type: "object",
                    properties: [
                        new OA\Property(
                            property: "data",
                            type: "array",
                            items: new OA\Items(
                                type: "object",
                                properties: [
                                    new OA\Property(property: "id", type: "integer", example: 6),
                                    new OA\Property(property: "status", type: "string", example: "NEW"),
                                    new OA\Property(property: "total_amount", type: "number", format: "float", example: 157.08),
                                    new OA\Property(property: "confirmed_at", type: "string", format: "date-time", nullable: true),
                                    new OA\Property(property: "shipped_at", type: "string", format: "date-time", nullable: true),
                                    new OA\Property(property: "created_at", type: "string", format: "date-time"),
                                    new OA\Property(property: "updated_at", type: "string", format: "date-time"),
                                    new OA\Property(
                                        property: "customer",
                                        type: "object",
                                        properties: [
                                            new OA\Property(property: "id", type: "integer", example: 4),
                                            new OA\Property(property: "name", type: "string", example: "Dagmar Simonis"),
                                            new OA\Property(property: "email", type: "string", example: "shaag@example.com"),
                                            new OA\Property(property: "phone", type: "string", example: "1-516-592-9711"),
                                            new OA\Property(property: "created_at", type: "string", format: "date-time"),
                                            new OA\Property(property: "updated_at", type: "string", format: "date-time")
                                        ]
                                    ),
                                    new OA\Property(
                                        property: "items",
                                        type: "array",
                                        items: new OA\Items(
                                            type: "object",
                                            properties: [
                                                new OA\Property(property: "id", type: "integer", example: 4),
                                                new OA\Property(property: "order_id", type: "integer", example: 6),
                                                new OA\Property(property: "quantity", type: "integer", example: 1),
                                                new OA\Property(property: "unit_price", type: "number", format: "float", example: 157.08),
                                                new OA\Property(property: "total_price", type: "number", format: "float", example: 157.08),
                                                new OA\Property(property: "created_at", type: "string", format: "date-time"),
                                                new OA\Property(property: "updated_at", type: "string", format: "date-time")
                                            ]
                                        )
                                    )
                                ]
                            )
                        )
                    ]
                )
            )
        ]
    )]
    public function index(OrderIndexRequest $request): AnonymousResourceCollection
    {
        $dto = $request->toDto();
        $result = $this->orderService->getOrders($dto);
        return OrderResource::collection($result);
    }




    #[OA\Get(
        path: "/api/orders/{id}",
        tags: ["Заказы"],
        summary: "Получение заказа по ID",
        operationId: "getOrderById",
        parameters: [
            new OA\Parameter(
                name: "id",
                in: "path",
                required: true,
                description: "ID заказа",
                schema: new OA\Schema(
                    type: "integer",
                    example: 8
                )
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Данные заказа",
                content: new OA\JsonContent(
                    type: "object",
                    properties: [
                        new OA\Property(
                            property: "data",
                            type: "object",
                            properties: [
                                new OA\Property(property: "id", type: "integer", example: 8),
                                new OA\Property(property: "status", type: "string", example: "NEW"),
                                new OA\Property(property: "total_amount", type: "number", format: "float", example: 157.08),
                                new OA\Property(property: "confirmed_at", type: "string", format: "date-time", nullable: true),
                                new OA\Property(property: "shipped_at", type: "string", format: "date-time", nullable: true),
                                new OA\Property(property: "created_at", type: "string", format: "date-time"),
                                new OA\Property(property: "updated_at", type: "string", format: "date-time"),
                                new OA\Property(
                                    property: "customer",
                                    type: "object",
                                    properties: [
                                        new OA\Property(property: "id", type: "integer", example: 4),
                                        new OA\Property(property: "name", type: "string", example: "Dagmar Simonis"),
                                        new OA\Property(property: "email", type: "string", example: "shaag@example.com"),
                                        new OA\Property(property: "phone", type: "string", example: "1-516-592-9711"),
                                        new OA\Property(property: "created_at", type: "string", format: "date-time"),
                                        new OA\Property(property: "updated_at", type: "string", format: "date-time")
                                    ]
                                ),
                                new OA\Property(
                                    property: "items",
                                    type: "array",
                                    items: new OA\Items(
                                        type: "object",
                                        properties: [
                                            new OA\Property(property: "id", type: "integer", example: 6),
                                            new OA\Property(property: "order_id", type: "integer", example: 8),
                                            new OA\Property(property: "quantity", type: "integer", example: 1),
                                            new OA\Property(property: "unit_price", type: "number", format: "float", example: 157.08),
                                            new OA\Property(property: "total_price", type: "number", format: "float", example: 157.08),
                                            new OA\Property(property: "created_at", type: "string", format: "date-time"),
                                            new OA\Property(property: "updated_at", type: "string", format: "date-time")
                                        ]
                                    )
                                )
                            ]
                        )
                    ]
                )
            ),
            new OA\Response(
                response: 404,
                description: "Заказ не найден",
                content: new OA\JsonContent(
                    type: "object",
                    properties: [
                        new OA\Property(property: "message", type: "string", example: "Заказ не найден")
                    ]
                )
            )
        ]
    )]
    public function show($id)
    {
        $result = $this->orderService->getOrderById((int)$id);

        return OrderResource::make($result);
    }



    #[OA\Patch(
        path: "/api/orders/{id}/status",
        tags: ["Заказы"],
        summary: "Обновление статуса заказа",
        operationId: "updateOrderStatus",
        parameters: [
            new OA\Parameter(
                name: "id",
                in: "path",
                required: true,
                description: "ID заказа",
                schema: new OA\Schema(
                    type: "integer",
                    example: 1
                )
            )
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                type: "object",
                required: ["status"],
                properties: [
                    new OA\Property(
                        property: "status",
                        type: "string",
                        description: "Новый статус заказа",
                        enum: ["NEW","CONFIRMED","PROCESSING","SHIPPED","COMPLETED"],
                        example: "CONFIRMED"
                    )
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: "Статус успешно обновлен",
                content: new OA\JsonContent(
                    type: "object",
                    properties: [
                        new OA\Property(property: "status", type: "boolean", example: true)
                    ]
                )
            ),
            new OA\Response(
                response: 404,
                description: "Заказ не найден",
                content: new OA\JsonContent(
                    type: "object",
                    properties: [
                        new OA\Property(property: "message", type: "string", example: "Заказ не найден")
                    ]
                )
            ),
            new OA\Response(
                response: 500,
                description: "Ошибка обновления статуса",
                content: new OA\JsonContent(
                    type: "object",
                    properties: [
                        new OA\Property(
                            property: "message",
                            type: "string",
                            example: "Недопустимый переход из CONFIRMED в CONFIRMED"
                        )
                    ]
                )
            )
        ]
    )]
    public function updateStatus(Request $request, int $id)
    {
        $result = $this->orderService->updateStatusById($id, $request->status);

        return response()->json(['success' => $result]);
    }
}