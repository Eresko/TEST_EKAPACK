<?php
namespace App\Services\OrderServices;

use App\Models\Order;
use Illuminate\Support\Facades\DB;
use App\Repository\OrderRepository;
use App\Repository\ProductRepository;
use App\Dto\Order\IndexOrderDto;
use Illuminate\Support\Collection;
use App\Dto\Order\CreateOrderDto;
use App\Jobs\ExportOrderJob;
use App\Enums\Order\StatusOrderEnum;
use Exception;

class OrderService
{
    public function __construct(
        private OrderRepository $orderRepository,
        private ProductRepository $productRepository
    ) {}

    public function createOrder(CreateOrderDto $dto): array
    {
        return DB::transaction(function () use ($dto) {

            $totalAmount = 0;
            $products = [];

            foreach ($dto->getItems() as $item) {
                $product = $this->productRepository->getProductById($item['product_id']);

                if (!$product) {
                    throw new \Exception("Продукт не найден");
                }

                if ($product->stock_quantity < $item['quantity']) {
                    throw new \Exception("Недостаточно товаров для продукта {$product->name}");
                }

                $products[$item['product_id']] = $product;
                $totalAmount += $item['quantity'] * $product->price;
            }

            $order = $this->orderRepository->createOrder(
                $dto->getCustomerId(),
                $totalAmount
            );

            foreach ($dto->getItems() as $item) {
                $product = $products[$item['product_id']];

                $this->orderRepository->createOrderItem(
                    $order->id,
                    $item['product_id'],
                    $item['quantity'],
                    $product->price,
                    $item['quantity'] * $product->price
                );

                $this->productRepository->decreaseStock(
                    $item['product_id'],
                    $item['quantity']
                );
            }

            return [
                'order_id' => $order->id,
                'status' => 'успешно создан'
            ];
        });
    }


    /**
     * @param IndexOrderDto $dto
     * @return Collection
     */
    public function getOrders(IndexOrderDto $dto):Collection {
        return $this->orderRepository->getOrderByFilter($dto);
    }

    /**
     * @param int $id
     * @return Order
     */
    public function getOrderById(int $id):Order {
        return $this->orderRepository->getOrderById($id);
    }

    /**
     * @param int $id
     * @param string $status
     * @return bool
     * @throws Exception
     */
    public function updateStatusById(int $id,string $status):bool {
        $order =  $this->orderRepository->getOrderById($id);
        if (!$order) {
            return false;
        }

        $currentStatus = $order->status;
        $transitions = StatusOrderEnum::getTransitions();

        if (!isset($transitions[$currentStatus]) || !in_array($status, $transitions[$currentStatus])) {
            throw new \Exception('Недопустимый переход из ' . $currentStatus . ' в ' . $status);
        }
        if ($status === 'confirmed') {
            ExportOrderJob::dispatch($order, $status);
            return true;
        }

        return $order->update(['status' => $status]);
    }
}