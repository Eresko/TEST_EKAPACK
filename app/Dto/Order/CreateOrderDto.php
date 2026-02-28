<?php
namespace App\Dto\Order;

use  Illuminate\Support\Collection;
class CreateOrderDto
{


    public function __construct(private int $customerId,private Collection $items)
    {
    }

    public function getCustomerId(): int {
        return $this->customerId;
    }

    public function getItems(): Collection {
        return $this->items;
    }
}