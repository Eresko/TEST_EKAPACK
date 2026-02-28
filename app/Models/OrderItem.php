<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
/**
 * Элемент заказа
 *
 * @property int        $id           Идентификатор
 * @property int        $order_id     Идентификатор заказа
 * @property int        $product_id   Идентификатор товара
 * @property int        $quantity     Количество
 * @property float      $unit_price   Цена за единицу
 * @property float      $total_price  Общая цена позиции 
 * @property \Carbon\Carbon|null  $created_at  Дата создания
 * @property \Carbon\Carbon|null  $updated_at  Дата обновления
 */

class OrderItem extends Model
{
    protected $table = 'order_items';

    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'unit_price',
        'total_price',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'unit_price' => 'float',
        'total_price' => 'float',
    ];


    public function order():BelongsTo
    {
        return $this->belongsTo(Order::class);
    }


    public function product():BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}