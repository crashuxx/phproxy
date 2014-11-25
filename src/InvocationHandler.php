<?php

namespace Reflection;


interface InvocationHandler
{
    /**
     * @param object $proxy
     * @param string $method
     * @param mixed[] $args
     * @return mixed
     */
    function invoke($proxy, $method, $args);
}