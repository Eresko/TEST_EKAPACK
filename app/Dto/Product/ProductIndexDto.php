<?php

namespace App\Dto\Product;

/**
 *  Для запроса продуктов
 */
class ProductIndexDto {

    public function __construct(
        private ?string $category,
        private ?string $search,
        private ?int $perPage = null,
        private ?int $page = null
    )
    {
    }

    public function getCategory(): ?string {
        return $this->category;
    }

    public function getSearch(): ?string {
        return $this->search;
    }

    public function getPerPage(): ?int {
        return $this->perPage;
    }

    public function getPage(): ?int {
        return $this->page;
    }
}