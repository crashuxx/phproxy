<?php

namespace Reflection\internal;


use Reflection\ProxyClass;

interface ProxyClassFactory
{
    /**
     * @param \ReflectionClass $reflectionClass
     * @param \ReflectionClass[] $interfaces
     * @return ProxyClass
     */
    public function get(\ReflectionClass $reflectionClass, $interfaces = []);
}