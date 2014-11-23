<?php

namespace Reflection\internal\Builder;


class StubbedBuilder implements Builder
{
    /**
     * @param string $namespace
     */
    public function writeNamespace($namespace)
    {
    }

    /**
     * @param string $new
     * @param string $baseClass
     * @param string[] $interfaces
     */
    public function writeClass($new, $baseClass, $interfaces)
    {
    }

    /**
     * @param \ReflectionMethod $method
     */
    public function writeMethod(\ReflectionMethod $method)
    {
    }

    /**
     */
    public function writeClose()
    {
    }

    /**
     * @return string
     */
    public function build()
    {
    }

    public function writeConstructor()
    {
    }

    /**
     * @return null
     */
    public function writeCallMethod()
    {
    }

    public function writeToStringMethod()
    {
    }
}