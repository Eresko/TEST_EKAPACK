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

class OrderController extends Controller
{

   
    public function __construct(private OrderService $orderService)
    {
        $this->middleware('rate.limit.create_orders')->only('create');
    }

    /**
     * @OA\Post(
     *     path="/api/orders",
     *     tags={"Заказы"},
     *     summary="Создание нового заказа",
     *     operationId="createOrder",
     *
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"customer_id","items"},
     *
     *             @OA\Property(
     *                 property="customer_id",
     *                 type="integer",
     *                 example=1,
     *                 description="ID клиента"
     *             ),
     *
     *             @OA\Property(
     *                 property="items",
     *                 type="array",
     *                 minItems=1,
     *                 @OA\Items(
     *                     type="object",
     *                     required={"product_id","quantity"},
     *
     *                     @OA\Property(
     *                         property="product_id",
     *                         type="integer",
     *                         example=10,
     *                         description="ID товара"
     *                     ),
     *
     *                     @OA\Property(
     *                         property="quantity",
     *                         type="integer",
     *                         minimum=1,
     *                         example=2,
     *                         description="Количество товара"
     *                     )
     *                 )
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=201,
     *         description="Заказ успешно создан",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="order_id",
     *                 type="integer",
     *                 example=7
     *             ),
     *             @OA\Property(
     *                 property="status",
     *                 type="string",
     *                 example="успешно создан"
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=400,
     *         description="Ошибка создания заказа",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="status",
     *                 type="string",
     *                 example="error"
     *             ),
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="Недостаточно товаров для продукта iPhone"
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=422,
     *         description="Ошибка валидации"
     *     )
     * )
     */
    public function store(StoreOrderRequest $request)
    {
        $dto = $request->toDto();

        $result = $this->orderService->createOrder($dto);

        return response()->json($result,201);
    }

    /**
     * @OA\Get(
     *     path="/api/orders",
     *     tags={"Заказы"},
     *     summary="Получение списка заказов",
     *     operationId="getOrders",
     *
     *     @OA\Parameter(
     *         name="status",
     *         in="query",
     *         description="Статус заказа",
     *         required=false,
     *         @OA\Schema(type="string", enum={"NEW","CONFIRMED","PROCESSING","SHIPPED","COMPLETED"})
     *     ),
     *
     *     @OA\Parameter(
     *         name="customer_id",
     *         in="query",
     *         description="ID клиента",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *
     *     @OA\Parameter(
     *         name="date_from",
     *         in="query",
     *         description="Дата начала периода (YYYY-MM-DD)",
     *         required=false,
     *         @OA\Schema(type="string", format="date")
     *     ),
     *
     *     @OA\Parameter(
     *         name="date_to",
     *         in="query",
     *         description="Дата окончания периода (YYYY-MM-DD)",
     *         required=false,
     *         @OA\Schema(type="string", format="date")
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Список заказов",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *
     *                     @OA\Property(property="id", type="integer", example=6),
     *                     @OA\Property(property="status", type="string", example="NEW"),
     *                     @OA\Property(property="total_amount", type="number", format="float", example=157.08),
     *                     @OA\Property(property="confirmed_at", type="string", format="date-time", nullable=true),
     *                     @OA\Property(property="shipped_at", type="string", format="date-time", nullable=true),
     *                     @OA\Property(property="created_at", type="string", format="date-time"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time"),
     *
     *                     @OA\Property(
     *                         property="customer",
     *                         type="object",
     *                         @OA\Property(property="id", type="integer", example=4),
     *                         @OA\Property(property="name", type="string", example="Dagmar Simonis"),
     *                         @OA\Property(property="email", type="string", example="shaag@example.com"),
     *                         @OA\Property(property="phone", type="string", example="1-516-592-9711"),
     *                         @OA\Property(property="created_at", type="string", format="date-time"),
     *                         @OA\Property(property="updated_at", type="string", format="date-time")
     *                     ),
     *
     *                     @OA\Property(
     *                         property="items",
     *                         type="array",
     *                         @OA\Items(
     *                             type="object",
     *                             @OA\Property(property="id", type="integer", example=4),
     *                             @OA\Property(property="order_id", type="integer", example=6),
     *                             @OA\Property(property="quantity", type="integer", example=1),
     *                             @OA\Property(property="unit_price", type="number", format="float", example=157.08),
     *                             @OA\Property(property="total_price", type="number", format="float", example=157.08),
     *                             @OA\Property(property="created_at", type="string", format="date-time"),
     *                             @OA\Property(property="updated_at", type="string", format="date-time")
     *                         )
     *                     )
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function index(OrderIndexRequest $request): AnonymousResourceCollection
    {
        $dto = $request->toDto();
        $result = $this->orderService->getOrders($dto);
        return OrderResource::collection($result);
    }




    /**
     * @OA\Get(
     *     path="/api/orders/{id}",
     *     tags={"Заказы"},
     *     summary="Получение заказа по ID",
     *     operationId="getOrderById",
     *
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID заказа",
     *         @OA\Schema(
     *             type="integer",
     *             example=8
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Данные заказа",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *
     *                 @OA\Property(property="id", type="integer", example=8),
     *                 @OA\Property(property="status", type="string", example="NEW"),
     *                 @OA\Property(property="total_amount", type="number", format="float", example=157.08),
     *                 @OA\Property(property="confirmed_at", type="string", format="date-time", nullable=true),
     *                 @OA\Property(property="shipped_at", type="string", format="date-time", nullable=true),
     *                 @OA\Property(property="created_at", type="string", format="date-time"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time"),
     *
     *                 @OA\Property(
     *                     property="customer",
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", example=4),
     *                     @OA\Property(property="name", type="string", example="Dagmar Simonis"),
     *                     @OA\Property(property="email", type="string", example="shaag@example.com"),
     *                     @OA\Property(property="phone", type="string", example="1-516-592-9711"),
     *                     @OA\Property(property="created_at", type="string", format="date-time"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time")
     *                 ),
     *
     *                 @OA\Property(
     *                     property="items",
     *                     type="array",
     *                     @OA\Items(
     *                         type="object",
     *                         @OA\Property(property="id", type="integer", example=6),
     *                         @OA\Property(property="order_id", type="integer", example=8),
     *                         @OA\Property(property="quantity", type="integer", example=1),
     *                         @OA\Property(property="unit_price", type="number", format="float", example=157.08),
     *                         @OA\Property(property="total_price", type="number", format="float", example=157.08),
     *                         @OA\Property(property="created_at", type="string", format="date-time"),
     *                         @OA\Property(property="updated_at", type="string", format="date-time")
     *                     )
     *                 )
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=404,
     *         description="Заказ не найден",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Заказ не найден")
     *         )
     *     )
     * )
     */
    public function show($id)
    {
        $result = $this->orderService->getOrderById((int)$id);

        return OrderResource::make($result);
    }



    /**
     * @OA\Patch(
     *     path="/api/orders/{id}/status",
     *     tags={"Заказы"},
     *     summary="Обновление статуса заказа",
     *     operationId="updateOrderStatus",
     *
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID заказа",
     *         @OA\Schema(
     *             type="integer",
     *             example=1
     *         )
     *     ),
     *
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"status"},
     *             @OA\Property(
     *                 property="status",
     *                 type="string",
     *                 description="Новый статус заказа",
     *                 enum={"NEW","CONFIRMED","PROCESSING","SHIPPED","COMPLETED"},
     *                 example="CONFIRMED"
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Статус успешно обновлен",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="boolean", example=true)
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=404,
     *         description="Заказ не найден",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Заказ не найден")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=500,
     *         description="Ошибка обновления статуса",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="Недопустимый переход из CONFIRMED в CONFIRMED"
     *             )
     *         )
     *     )
     * )
     */
    public function updateStatus(Request $request, int $id)
    {
        $result = $this->orderService->updateStatusById($id, $request->status);

        return response()->json(['success' => $result]);
    }
}