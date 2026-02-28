<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Dto\Product\ProductIndexDto;

class ProductIndexRequest extends FormRequest
{
    public function authorize()
    {
        // Включите авторизацию по необходимости
        return true;
    }

    public function rules()
    {
        return [
            'category' => 'nullable|string',
            'search' => 'nullable|string',
            'perPage' => 'nullable|integer|min:1',
            'page' => 'nullable|integer|min:1',
        ];
    }

    /**
     * Преобразуем входные данные в DTO
     */
    public function toDto(): ProductIndexDto
    {
        return new ProductIndexDto(
            category: $this->input('category'),
            search: $this->input('search'),
            perPage: $this->input('perPage'),
            page: $this->input('page')
        );
    }
}