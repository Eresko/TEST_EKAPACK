<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
/**
 * Клиент
 *
 * @property int         $id        Идентификатор
 * @property string      $name      Имя клиента
 * @property string      $email     Электронная почта
 * @property string      $phone     Телефон
 * @property \Carbon\Carbon|null  $created_at  Дата создания
 * @property \Carbon\Carbon|null  $updated_at  Дата обновления
 */

class Customer extends Model
{
    use HasFactory;
    protected $table = 'customers';

    protected $fillable = [
        'name',
        'email',
        'phone',
    ];
    
    protected $casts = [
        'email' => 'string',
        'phone' => 'string',
    ];
}