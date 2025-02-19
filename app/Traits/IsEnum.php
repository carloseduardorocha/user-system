<?php

namespace App\Traits;

trait IsEnum
{
    /**
     * Returns value of $key
     * @param string $key
     * @return mixed
     */
    public static function valueOf(string $key): mixed
    {
        foreach (static::cases() as $enum)
        {
            if ($enum->name === strtoupper($key))
            {
                return $enum->value;
            }
        }

        return null;
    }

    /**
     * Returns case of string $key
     * @param string $key
     * @return static|null
     */
    public static function caseOf(string $key): ?self
    {
        foreach (static::cases() as $enum)
        {
            if ($enum->name === strtoupper($key))
            {
                return $enum;
            }
        }

        return null;
    }

    /**
     * Returns array of enum names.
     *
     * @return array<string>
     */
    public static function names(): array
    {
        return array_column(static::cases(), 'name');
    }

    /**
     * Returns array of enum values.
     *
     * @return array<mixed>
     */
    public static function values(): array
    {
        return array_column(static::cases(), 'value');
    }

    /**
     * Returns enum into array $key => $value.
     *
     * @return array<string, mixed>
     */
    public static function toArray(): array
    {
        return array_combine(static::names(), static::values());
    }

    /**
     * Returns if param $compare is equals to enum value.
     *
     * @param mixed $compare
     * @return bool
     */
    public function equals(mixed $compare): bool
    {
        if ($compare instanceof self || is_string($compare) || is_int($compare) || is_bool($compare))
        {
            $value = $compare instanceof self ? $compare->value : $compare;
        }
        else
        {
            return false;
        }

        return $this->value === $value;
    }

    /**
     * Returns if ENUM value is equal to one of the options.
     *
     * @param array<mixed> $compare
     * @return bool
     */
    public function has(array $compare): bool
    {
        foreach ($compare as $comp)
        {
            if ($this->equals($comp))
            {
                return true;
            }
        }

        return false;
    }

    /**
     * Similar to "get," this name implies that the method retrieves something specific (in this case, an enum value).
     *
     * @param object|string $key
     * @return mixed
     */
    public static function retrieve(object|string $key): mixed
    {
        if (is_object($key) && is_a($key, self::class))
        {
            $key = $key->value;
        }

        return $key;
    }

    /**
     * Get all values from a specific method
     *
     * @param string $target
     * @param mixed ...$args
     * @return array<mixed>
     */
    public static function all(string $target = 'slug', ...$args): array
    {
        $values = [];

        foreach (static::cases() as $case)
        {
            $method   = $target;
            $values[] = $case->{$method}(...$args);
        }

        return $values;
    }
}
