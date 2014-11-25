<?php

namespace Reflection\internal\hhvm;

/**
 * Class ReflectionClass
 * @package Reflection\internal\hhvm
 *
 * HHVM bug when trying to invoke method from parent class by ReflectionMethod->invoke
 * this class is to overcome this behavior an call the parent method
 */
class ReflectionClass extends \ReflectionClass
{
    public function getConstructor()
    {
        $method = parent::getConstructor();
        return $method !== null ? new ReflectionMethod($this->getName(), $method->getName()) : null;
    }

    public function getMethod($name)
    {
        $method = parent::getMethod($name);
        return $method !== null ? new ReflectionMethod($this->getName(), $method->getName()) : null;
    }

    public function getMethods($filter = null)
    {
        $result = [];
        $methods = parent::getMethods($filter);

        foreach ($methods as $method) {
            $result[] = new ReflectionMethod($this->getName(), $method->getName());
        }

        return $result;
    }

    public function getParentClass()
    {
        $parent = parent::getParentClass();
        return $parent != null ? new ReflectionClass($parent->getName()) : null;
    }
}