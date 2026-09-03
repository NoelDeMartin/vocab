<?php

namespace App\Support\Macros;

use ReflectionClass;

class MacroMixin
{
    /**
     * The target instance.
     *
     * @var object
     */
    protected $target;

    /**
     * The reflected instance.
     *
     * @var ReflectionClass<object>
     */
    protected $reflected;

    public function __construct(object $target)
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

        return $property->getValue($this->target);
    }

    /**
     * Set target's property.
     */
    public function __set(string $name, mixed $value): void
    {
        $property = $this->reflected->getProperty($name);

        $property->setValue($this->target, $value);
    }

    /**
     * Call target's method.
     *
     * @param  array<mixed>  $arguments
     */
    public function __call(string $name, array $arguments): mixed
    {
        $method = $this->reflected->getMethod($name);

        return $method->invoke($this->target, ...$arguments);
    }
}
