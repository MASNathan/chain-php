<?php

namespace MASNathan\Chain;

class Item
{
    /**
     * @var mixed
     */
    protected $value;

    /**
     * @var string
     */
    protected static $identifier = '$$';

    /**
     * Item constructor.
     *
     * @param mixed $value
     */
    public function __construct($value)
    {
        $this->value = $value;
    }

    /**
     * @param string $identifier
     */
    public static function setIdentifier($identifier)
    {
        self::$identifier = $identifier;
    }

    /**
     * @param string|callable $name
     * @param array           $arguments
     * @return Item
     */
    public function __call($name, $arguments)
    {
        $arguments = $this->handleArguments($arguments);

        if (is_string($name) && !function_exists($name)) {
            $name = strtolower(implode('_', $this->splitCamelCase($name)));
        }

        return new self($name(...$arguments));
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return (string)$this->value;
    }

    /**
     * @param array $arguments
     * @return array
     */
    protected function handleArguments(array $arguments)
    {
        if (in_array(static::$identifier, $arguments, true)) {
            return array_map(function ($argument) {
                return $argument === static::$identifier ? $this->value : $argument;
            }, $arguments);
        }

        array_unshift($arguments, $this->value);

        return $arguments;
    }

    /**
     * @param string $string
     * @return array
     */
    protected function splitCamelCase($string)
    {
        $regex = '/(?#! splitCamelCase Rev:20140412)
            # Split camelCase "words". Two global alternatives. Either g1of2:
              (?<=[a-z])      # Position is after a lowercase,
              (?=[A-Z])       # and before an uppercase letter.
            | (?<=[A-Z])      # Or g2of2; Position is after uppercase,
              (?=[A-Z][a-z])  # and before upper-then-lower case.
            /x';

        return preg_split($regex, $string);
    }

    /**
     * @param string|array|callable $callback
     * @param array                 ...$arguments
     * @return Item
     */
    public function chain($callback, ...$arguments)
    {
        return $this->__call($callback, $arguments);
    }

    /**
     * @param string $key
     * @return Item
     */
    public function find($key)
    {
        return new self($this->get($key));
    }

    /**
     * @param string $key
     * @param mixed  $default
     * @return mixed
     */
    public function get($key = null, $default = null)
    {
        if (is_null($key)) {
            return $this->value ?: $default;
        }

        if (is_array($this->value) && isset($this->value[$key])) {
            return $this->value[$key] ?: $default;
        }

        if (is_object($this->value) && property_exists($this->value, $key)) {
            return $this->value->$key ?: $default;
        }

        return $default;
    }
}
