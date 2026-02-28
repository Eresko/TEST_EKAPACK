<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Dto\Order\CreateOrderDto;

class StoreOrderRequest extends FormRequest
{
    
    public function rules()
    {
        return [
            'customer_id' => 'required|exists:customers,id',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
        ];
    }

    public function toDto(): CreateOrderDto
    {
        return new CreateOrderDto(
            (int) $this->input('customer_id'),
            collect($this->input('items'))->map(function ($item) {
                return [
                    'product_id' => (int) $item['product_id'],
                    'quantity' => (int) $item['quantity']
                ];
            })
        );
    }
}