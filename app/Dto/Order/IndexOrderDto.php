<?php

namespace App\Dto\Order;

/**
 *  Для получения списка заказов
 */
class IndexOrderDto
{
    public function __construct(
        private ?string $status,
        private ?int $customerId,
        private ?string $dateFrom,
        private ?string $dateTo
    ) {
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function getCustomerId(): ?int
    {
        return $this->customerId;
    }

    public function getDateFrom(): ?string
    {
        return $this->dateFrom;
    }

    public function getDateTo(): ?string
    {
        return $this->dateTo;
    }
}