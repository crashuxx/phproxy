<?php

namespace Reflection\InvocationHandler;


use Reflection\InvocationHandler;

class DummyInvocationHandler implements InvocationHandler
{
    /**
     * @param object $proxy
     * @param string $method
     * @param mixed[] $args
     * @return mixed
     */
    function invoke($proxy, $method, $args)
    {
        throw new \RuntimeException('Cannot call method on dummy invocation handler!');
    }
}