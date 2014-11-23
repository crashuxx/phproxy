<?php

namespace Reflection;


use Reflection\InvocationHandler;

interface ProxyClass
{
    function newInstance(InvocationHandler $handler);

    /**
     * @return \ReflectionClass
     */
    public function getParentClass();
}