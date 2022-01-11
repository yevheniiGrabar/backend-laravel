<?php

namespace App\Enums;

/**
 * Base class for enumerations.
 */
abstract class AbstractEnum
{
    /**
     * Static cache of available values, shared with all subclasses.
     *
     * @var array
     */
    protected static array $values = [];

    /**
     * AbstractEnum constructor.
     */
    private function __construct()
    {
    }

    /**
     * @throws \Exception
     */
    public function __clone()
    {
        throw new \Exception("Cannot clone " . static::class);
    }

    /**
     * Gets all available keys with values.
     *
     * @throws \ReflectionException
     *
     * @return array The available values, keyed by constant.
     */
    public static function getAll()
    {
        $class = static::class;

        if (!isset(static::$values[$class])) {
            $reflection = new \ReflectionClass($class);
            static::$values[$class] = $reflection->getConstants();
        }

        return static::$values[$class];
    }

    /**
     * Gets all available values.
     *
     * @throws \ReflectionException
     *
     * @return array The available values, keyed by constant.
     */
    public static function getAllValues()
    {
        return array_values(static::getAll());
    }

    /**
     * Gets all available values as keys.
     *
     * @throws \ReflectionException
     *
     * @return array The available values, keyed by constant.
     */
    public static function getAllValuesAsKeys()
    {
        return array_flip(static::getAll());
    }

    /**
     * Gets all available keys.
     *
     * @throws \ReflectionException
     *
     * @return array The available values, keyed by constant.
     */
    public static function getAllKeys()
    {
        return array_keys(static::getAll());
    }

    /**
     * Gets the key of the provided value.
     *
     * @param string $value The value.
     *
     * @throws \ReflectionException
     *
     * @return bool The key if found, false otherwise.
     */
    public static function getKey($value)
    {
        return array_search($value, static::getAll(), true);
    }

    /**
     * Checks whether the provided value is defined.
     *
     * @param string $value The value.
     *
     * @throws \ReflectionException
     *
     * @return bool True if the value is defined, false otherwise.
     */
    public static function exists($value)
    {
        return in_array($value, static::getAll(), true);
    }

    /**
     * Asserts that the provided value is defined.
     *
     * @param string $value The value.
     *
     * @throws \InvalidArgumentException
     * @throws \ReflectionException
     */
    public static function assertExists($value)
    {
        if (static::exists($value) == false) {
            $class = substr(strrchr(get_called_class(), '\\'), 1);
            throw new \InvalidArgumentException(sprintf('"%s" is not a valid %s value.', $value, $class));
        }
    }

    /**
     * Asserts that all provided valus are defined.
     *
     * @param array $values The values.
     *
     * @throws \ReflectionException
     */
    public static function assertAllExist(array $values)
    {
        foreach ($values as $value) {
            static::assertExists($value);
        }
    }

    /**
     * Glue all values
     *
     * @param string $glue
     *
     * @throws \ReflectionException
     *
     * @return string
     */
    public static function implode($glue = ',')
    {
        return implode($glue, static::getAll());
    }
}
