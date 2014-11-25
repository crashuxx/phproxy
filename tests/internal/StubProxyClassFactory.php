<?php

namespace Reflection\internal;


use Reflection\ProxyClass;

class StubProxyClassFactory implements ProxyClassFactory
{
    /**
     * @param \ReflectionClass $reflectionClass
     * @param \ReflectionClass[] $interfaces
     * @return ProxyClass
     */
    public function get(\ReflectionClass $reflectionClass, $interfaces = [])
    {
        return new DummyProxyClass();
    }
}