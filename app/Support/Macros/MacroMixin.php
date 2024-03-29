<?php

namespace App\Support\Macros;

use ReflectionClass;

class MacroMixin
{
    /**
     * The target instance.
     *
     * @var mixed
     */
    protected $target;

    /**
     * The reflected instance.
     *
     * @var ReflectionClass
     */
    protected $reflected;

    /**
     * @param  mixed  $target
     */
    public function __construct($target)
    {
        $this->target = $target;
        $this->reflected = new ReflectionClass($target);
    }

    /**
     * Get target's property.
     */
    public function __get(string $name): mixed
    {
        $property = $this->reflected->getProperty($name);

        $property->setAccessible(true);

        return $property->getValue($this->target);
    }

    /**
     * Set target's property.
     */
    public function __set(string $name, mixed $value): void
    {
        $property = $this->reflected->getProperty($name);

        $property->setAccessible(true);

        $property->setValue($this->target, $value);
    }

    /**
     * Call target's method.
     *
     * @param  string  $name
     * @param  array  $arguments
     */
    public function __call($name, $arguments): mixed
    {
        $method = $this->reflected->getMethod($name);

        $method->setAccessible(true);

        return $method->invoke($this->target, ...$arguments);
    }
}
