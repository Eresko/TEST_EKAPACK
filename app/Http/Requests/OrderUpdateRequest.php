<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Dto\Order\IndexOrderDto;
use App\Enums\Order\StatusOrderEnum;

class OrderUpdateRequest extends FormRequest
{
    public function rules()
    {
        $statusValues = array_map(function($status) {
            return $status['event'];
        }, StatusOrderEnum::ALL);

        return [
            'status' => [
                'sometimes',
                'string',
                'in:' . implode(',', $statusValues), 
            ],
        ];
    }
}