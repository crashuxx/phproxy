<?php

namespace Reflection\internal;


use Reflection\InvocationHandler;
use Reflection\ProxyClass;
use Symfony\Component\Yaml\Exception\RuntimeException;

class DummyProxyClass implements ProxyClass
{
    function newInstance(InvocationHandler $handler)
    {
        throw new RuntimeException("Call on dummy class is forbidden!");
    }

    public function getParentClass()
    {
        return null;
    }
}