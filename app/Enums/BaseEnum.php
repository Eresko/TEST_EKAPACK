<?php

declare(strict_types=1);

namespace App\Enums;

use App\Exceptions\WrongEnumException;
use Illuminate\Support\Collection;

abstract class BaseEnum extends Enum
{
    private $value;

    /**
     * @throws WrongEnumException
     */
    public function __construct($value)
    {
        if (!self::contains($value)) {
            throw new WrongEnumException(get_called_class(), $value);
        }
        $this->value = $value;
    }

    public function __toString(): string
    {
        return (string)$this->getValue();
    }

    /**
     * @return Collection<BaseEnum>
     */
    public static function allAsEnums(): Collection
    {
        return collect(static::all())->map(function ($value) {
            return new static($value);
        });
    }

    public function getValue()
    {
        return $this->value;
    }
}
