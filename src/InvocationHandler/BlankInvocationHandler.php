<?php

namespace Reflection\InvocationHandler;


use Reflection\InvocationHandler;

class BlankInvocationHandler implements InvocationHandler
{
    /**
     * @param object $proxy
     * @param string $method
     * @param mixed[] $args
     * @return mixed
     */
    function invoke($proxy, $method, $args)
    {
        // do nothing
        return $method == '__toString' ? '' : null;
    }
}