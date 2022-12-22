<?php

use App\Support\Macros\BetterMacros;
use App\Support\Macros\MacroMixin;
use Illuminate\Support\Traits\Macroable;

test('class based mixins', function () {
    BetterMacros::mixin(TestMacroable::class, TestMixinClass::class);
    $instance = new TestMacroable;
    $this->assertSame('instance-Adam', $instance->methodOne('Adam'));
});

test("class based mixins don't replace", function () {
    TestMacroable::macro('methodThree', function () {
        return 'bar';
    });
    BetterMacros::mixin(TestMacroable::class, TestMixinClass::class, false);
    $instance = new TestMacroable;
    $this->assertSame('bar', $instance->methodThree());

    BetterMacros::mixin(TestMacroable::class, TestMixinClass::class);
    $this->assertSame('foo', $instance->methodThree());
});

test('class based mixins guard against non-macroables', function () {
    BetterMacros::mixin(TestMixinClass::class, TestMacroable::class);
})
    ->throws(Exception::class, 'Macro mixins can only be applied to Macroable classes.');

class TestMacroable
{
    use Macroable;

    protected $protectedVariable = 'instance';

    protected static function getProtectedStatic()
    {
        return 'static';
    }
}

class TestMixinClass extends MacroMixin
{
    public function methodOne($value)
    {
        return $this->methodTwo($value);
    }

    protected function methodTwo($value)
    {
        return $this->protectedVariable.'-'.$value;
    }

    protected function methodThree()
    {
        return 'foo';
    }
}
