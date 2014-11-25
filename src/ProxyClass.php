<?php

namespace Reflection;


use Reflection\InvocationHandler;

interface ProxyClass
{
    /**
     * @param InvocationHandler $handler
     * @return object
     */
    function newInstance(InvocationHandler $handler);

    /**
     * @return \ReflectionClass
     */
    function getParentClass();
}