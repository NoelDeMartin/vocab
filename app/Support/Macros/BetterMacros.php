<?php

namespace App\Support\Macros;

use Exception;
use Illuminate\Support\Traits\Macroable;
use ReflectionClass;
use ReflectionMethod;
use WeakMap;

class BetterMacros
{
    /**
     * Mixin instances.
     *
     * @var WeakMap|null
     */
    protected static $mixins;

    /**
     * Apply class mixins.
     *
     * @param  string  $baseClass
     * @param  string  $mixinClass
     * @param  bool  $replace
     * @return void
     *
     * @throws \ReflectionException
     */
    public static function mixin(string $baseClass, string $mixinClass, bool $replace = true)
    {
        if (! in_array(Macroable::class, class_uses_recursive($baseClass))) {
            throw new Exception('Macro mixins can only be applied to Macroable classes.');
        }

        $instances = static::$mixins ??= new WeakMap();
        $methods = (new ReflectionClass($mixinClass))->getMethods(
            ReflectionMethod::IS_PUBLIC | ReflectionMethod::IS_PROTECTED
        );

        foreach ($methods as $method) {
            if ($method->name === '__constructor' || (! $replace && $baseClass::hasMacro($method->name))) {
                continue;
            }

            $baseClass::macro($method->name, function (...$args) use ($instances, $mixinClass, $method) {
                // @phpstan-ignore-next-line
                $instance = $instances[$this] ??= new $mixinClass($this);

                return $method->invoke($instance, ...$args);
            });
        }
    }
}
