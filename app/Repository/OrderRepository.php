<?php

namespace App\Repository;

use App\Models\Order;
use App\Models\OrderItem;
use App\Dto\Order\IndexOrderDto;
use Illuminate\Support\Collection;
class OrderRepository
{

    /**
     * @param int $customerId
     * @return Order
     */
    public function createOrder(int $customerId,float $totalAmount): Order
    {
        return Order::create(['customer_id' => $customerId,'total_amount' => $totalAmount]);
    }


    /**
     * @param int $orderId
     * @param int $productId
     * @param int $quantity
     * @return OrderItem
     */
    public function createOrderItem(int $orderId, int $productId, int $quantity,float $unitPrice,float $totalPrice): OrderItem
    {
        return OrderItem::create([
            'order_id' => $orderId,
            'product_id' => $productId,
            'quantity' => $quantity,
            'unit_price' => $unitPrice,
            'total_price' => $totalPrice,
        ]);
    }


    /**
     * @param IndexOrderDto $dto
     * @return Collection
     */
    public function getOrderByFilter(IndexOrderDto $dto):Collection {

        $query = Order::with(['items', 'customer']);

        if ($dto->getStatus()) {
            $query->where('status', $dto->getStatus());
        }
        if ($dto->getCustomerId()) {
            $query->where('customer_id', $dto->getCustomerId());
        }
        if ($dto->getDateFrom()) {
            $query->where('created_at', '>=', $dto->getDateFrom());
        }
        if ($dto->getDateTo()) {
            $query->where('created_at', '<=', $dto->getDateTo());
        }

        return $query->get();

    }

    /**
     * @param int $id
     * @return Order|null
     */

    public function getOrderById(int $id):?Order {
        return Order::with(['items', 'customer'])->where('id',$id)->first();
    }


}