<?php

namespace Reflection\internal;


use Reflection\ProxyClass;

class CachedProxyClassFactory implements ProxyClassFactory
{
    private $classMap = [];

    /**
     * @var ProxyClassFactory
     */
    private $enhancedClassFactory;

    /**
     * @param ProxyClassFactory $enhancedClassFactory
     */
    function __construct(ProxyClassFactory $enhancedClassFactory)
    {
        $this->enhancedClassFactory = $enhancedClassFactory;
    }

    /**
     * @param \ReflectionClass $reflectionClass
     * @param \ReflectionClass[] $interfaces
     * @return ProxyClass
     */
    public function get(\ReflectionClass $reflectionClass, $interfaces = [])
    {
        $key = $this->makeKey([$reflectionClass] + $interfaces);

        if (!isset($this->classMap[$key])) {
            $this->classMap[$key] = $this->enhancedClassFactory->get($reflectionClass, $interfaces);
        }

        return $this->classMap[$key];
    }

    /**
     * @param \ReflectionClass[] $reflectionClasses
     * @return string
     */
    private function makeKey($reflectionClasses)
    {
        $names = [];

        foreach ($reflectionClasses as $reflectionClass) {
            /** @var \ReflectionClass $reflectionClass */
            $names[] = $reflectionClass->getName();
        }

        return serialize($names);
    }
}