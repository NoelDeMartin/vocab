<?php

use App\Support\Macros\BetterMacros;
use App\Support\Macros\MacroMixin;
use Illuminate\Support\Traits\Macroable;

it('supports class based mixins', function () {
    BetterMacros::mixin(TestMacroable::class, TestMixin::class);
    $instance = new TestMacroable;
    expect($instance->methodOne('Adam'))->toBe('instance-Adam');
});

it("doesn't replace existing macros in class based mixins", function () {
    TestMacroable::macro('methodThree', function () {
        return 'bar';
    });
    BetterMacros::mixin(TestMacroable::class, TestMixin::class, false);
    $instance = new TestMacroable;
    expect($instance->methodThree())->toBe('bar');

    BetterMacros::mixin(TestMacroable::class, TestMixin::class);
    expect($instance->methodThree())->toBe('foo');
});

it('guards against non-macroables in class based mixins', function () {
    BetterMacros::mixin(TestMixin::class, TestMacroable::class);
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

class TestMixin extends MacroMixin
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
