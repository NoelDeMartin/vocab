<?php

namespace App\Support;

use WeakMap;

class Macros
{
    protected static $instances;

    public static function mixin(string $baseClass, string $mixinClass)
    {
        $instances = static::$instances ??= new WeakMap();
        $macros = array_filter(get_class_methods($mixinClass), fn ($method) => $method !== 'boot');

        foreach ($macros as $macro) {
            $baseClass::macro($macro, function () use ($instances, $mixinClass, $macro) {
                // @phpstan-ignore-next-line
                $instance = $instances[$this] ??= new $mixinClass($this);

                return $instance->$macro();
            });
        }
    }
}
