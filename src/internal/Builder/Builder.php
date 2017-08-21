<?php

namespace Reflection\internal\Builder;


use ReflectionMethod;

interface Builder
{
    /**
     * @param string $namespace
     */
    public function writeNamespace($namespace);

    /**
     * @param string $new
     * @param string $baseClass
     * @param string[] $interfaces
     */
    public function writeClass($new, $baseClass, $interfaces);

    /**
     * @param \ReflectionMethod $method
     */
    public function writeMethod(\ReflectionMethod $method);

    /**
     * @return null
     */
    public function writeCallMethod(ReflectionMethod $method = null);

    /**
     */
    public function writeClose();

    /**
     * @return string
     */
    public function build();

    public function writeConstructor();

    public function writeToStringMethod();
}