<?php

namespace Reflection\internal\hhvm;

function invoker()
{
    return function ($method, $args) {
        return call_user_func_array('parent::' . $method, $args);
    };
}

;

/**
 * Class ReflectionMethod
 * @package Reflection\internal\hhvm
 *
 * HHVM bug when trying to invoke method from parent class by ReflectionMethod->invoke
 * this class is to overcome this behavior an call the parent method
 */
class ReflectionMethod extends \ReflectionMethod
{
    public function invoke($object, $parameter = null, $_ = null)
    {
        $args = func_get_args();
        array_shift($args);

        /** @noinspection PhpUndefinedMethodInspection */
        $invoker = invoker()->bindTo($object, get_class($object));
        return $invoker($this->getName(), $args);
    }

    public function invokeArgs($object, array $args)
    {
        /** @noinspection PhpUndefinedMethodInspection */
        $invoker = invoker()->bindTo($object, get_class($object));
        return $invoker($this->getName(), $args);
    }
}