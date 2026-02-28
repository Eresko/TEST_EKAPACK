<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Dto\Order\IndexOrderDto;

class OrderIndexRequest extends FormRequest
{
    public function rules()
    {
        return [
            'status' => 'sometimes|string',
            'customer_id' => 'sometimes|integer|exists:customers,id',
            'date_from' => 'sometimes|date',
            'date_to' => 'sometimes|date',
        ];
    }

    /**
     * Создает и возвращает DTO на основе текущих данных запроса
     */
    public function toDto(): IndexOrderDto
    {
        $validated = $this->validated();

        return new IndexOrderDto(
            $this->input('status') ?? null,
            $this->input('customer_id') ?? null,
            $this->input('date_from') ?? null,
            $this->input('date_to') ?? null
        );
    }
}