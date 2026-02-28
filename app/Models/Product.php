<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Продукт
 *
 * @property int         $id                Идентификатор
 * @property string      $name              Название товара
 * @property string      $sku               Артикул
 * @property float       $price             Цена
 * @property int         $stock_quantity    Количество
 * @property string      $category          Категория
 * @property \Carbon\Carbon|null  $created_at  Дата создания
 * @property \Carbon\Carbon|null  $updated_at  Дата обновления
 */

class Product extends Model
{
    use HasFactory;
    protected $table = 'products';

    protected $fillable = [
        'name',
        'sku',
        'price',
        'stock_quantity',
        'category',
    ];

    protected $casts = [
        'price' => 'float',
        'stock_quantity' => 'integer',
    ];


}