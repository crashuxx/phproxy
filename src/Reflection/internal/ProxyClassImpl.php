<?php

namespace Reflection\internal;


use Reflection\internal\hhvm\ReflectionClass;
use Reflection\InvocationHandler;
use Reflection\ProxyClass;

class ProxyClassImpl implements ProxyClass
{
    /**
     * @var string
     */
    private $enhancedClassName;
    /**
     * @var \ReflectionClass
     */
    private $reflectionClass;
    /**
     * @var \ReflectionClass[]
     */
    private $interfaces;

    /**
     * @param string $enhancedClassName
     * @param \ReflectionClass $reflectionClass
     * @param \ReflectionClass[] $interfaces
     */
    public function __construct($enhancedClassName, \ReflectionClass $reflectionClass, $interfaces)
    {
        $this->enhancedClassName = $enhancedClassName;
        $this->reflectionClass = $reflectionClass;
        $this->interfaces = $interfaces;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->enhancedClassName;
    }

    /**
     * @return \ReflectionClass
     */
    public function getParentClass()
    {
        if (defined('HHVM_VERSION')) {
            return new ReflectionClass($this->reflectionClass->getName());
        }

        return $this->reflectionClass;
    }

    public function newInstance(InvocationHandler $handler)
    {
        return new $this->enhancedClassName($handler);
    }
}