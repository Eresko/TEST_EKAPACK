<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\hasMany;
/**
 * Заказ
 *
 * @property int        $id             Идентификатор
 * @property int        $customer_id    Идентификатор клиента
 * @property string     $status         Статус заказа
 * @property float      $total_amount   Итоговая сумма
 * @property \Carbon\Carbon|null  $confirmed_at  Дата подтверждения
 * @property \Carbon\Carbon|null  $shipped_at    Дата отправки
 * @property \Carbon\Carbon|null  $created_at    Дата создания
 * @property \Carbon\Carbon|null  $updated_at    Дата обновления
 */

class Order extends Model
{
    protected $table = 'orders';

    protected $fillable = [
        'customer_id',
        'status',
        'total_amount',
        'confirmed_at',
        'shipped_at',
    ];

    protected $casts = [
        'total_amount' => 'float',
        'confirmed_at' => 'datetime',
        'shipped_at' => 'datetime',
    ];

    /**
     * @return BelongsTo
     */
    public function customer():BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function items():hasMany
    {
        return $this->hasMany(OrderItem::class);
    }
}