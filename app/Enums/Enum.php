<?php

declare(strict_types=1);

namespace App\Enums;

use App\Exceptions\WrongEnumException;
use ReflectionClass;

abstract class Enum
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

    public static function __callStatic($name, $arguments)
    {
        $className = get_called_class();
        $cls = new ReflectionClass($className);
        $constants = $cls->getConstants();
        if (!isset($constants[$name])) {
            throw new WrongEnumException(get_called_class(), $name);
        }

        return new $className($constants[$name]);
    }

    public static function contains($needle): bool
    {
        return in_array($needle, self::all(), true);
    }

    public static function all(): array
    {
        $cls = new ReflectionClass(get_called_class());

        return array_values($cls->getConstants());
    }

    public function getValue()
    {
        return $this->value;
    }

    public function is($value): bool
    {
        if ($value instanceof self) {
            $value = $value->getValue();
        }

        return $this->getValue() === $value;
    }

    public function isNot($value): bool
    {
        return !$this->is($value);
    }

    public function of(...$args): bool
    {
        foreach ($args as $status) {
            if ($this->is($status)) {
                return true;
            }
        }

        return false;
    }
}
