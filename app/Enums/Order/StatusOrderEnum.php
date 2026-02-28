<?php

declare(strict_types=1);

namespace App\Enums\Order;

use App\Enums\BaseEnum;

/**
 * Order status.
 *
 * @method static NEW
 * @method static CONFIRMED
 * @method static PROCESSING
 * @method static SHIPPED
 * @method static COMPLETED
 * @method static CANCELLED
 */
class StatusOrderEnum extends BaseEnum
{
    /** Статус "Новый" */
    const NEW = 'NEW';

    /** Статус "Подтвержден" */
    const CONFIRMED = 'CONFIRMED';

    /** Статус "Обрабатывается" */
    const PROCESSING = 'PROCESSING';

    /** Статус "Отправлен" */
    const SHIPPED = 'SHIPPED';

    /** Статус "Завершен" */
    const COMPLETED = 'COMPLETED';

    /** Статус "Отменен" */
    const CANCELLED = 'CANCELLED';

    /** Все варианты статусов */
    const ALL = [
        [
            'event' => 'NEW',
            'text' => 'Новый заказ',
        ],
        [
            'event' => 'CONFIRMED',
            'text' => 'Подтвержден',
        ],
        [
            'event' => 'PROCESSING',
            'text' => 'Обрабатывается',
        ],
        [
            'event' => 'SHIPPED',
            'text' => 'Отправлен',
        ],
        [
            'event' => 'COMPLETED',
            'text' => 'Завершен',
        ],
        [
            'event' => 'CANCELLED',
            'text' => 'Отменен',
        ],
    ];

    public static function getTransitions(): array
    {
        return [
            self::NEW => [self::CONFIRMED, self::CANCELLED],
            self::CONFIRMED => [self::PROCESSING, self::CANCELLED],
            self::PROCESSING => [self::SHIPPED, self::CANCELLED],
            self::SHIPPED => [self::COMPLETED],
            self::COMPLETED => [],
            self::CANCELLED => [],
        ];
    }
}