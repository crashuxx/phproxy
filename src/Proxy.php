<?php

namespace Reflection;


use Reflection\internal\Builder\BuilderFactory;
use Reflection\internal\CachedProxyClassFactory;
use Reflection\internal\Evaluator\EvalEvaluator;
use Reflection\internal\ProxyClassFactory;
use Reflection\internal\ProxyClassFactoryImpl;
use Reflection\internal\ProxyMark;

class Proxy
{
    private static $instance;
    /**
     * @var ProxyClassFactory
     */
    private $proxyClassFactory;

    private function __construct()
    {
        $this->proxyClassFactory = $this->createProxyClassFactory();
    }

    /**
     * @return ProxyClassFactory
     */
    private function createProxyClassFactory()
    {
        return new CachedProxyClassFactory(new ProxyClassFactoryImpl(new BuilderFactory(), new EvalEvaluator()));
    }

    /**
     * Return ProxyClass for given class
     *
     * @param string|string[] $classOrInterfaces
     * @return ProxyClass
     */
    public static function getProxyClass($classOrInterfaces)
    {
        return self::instance()->get($classOrInterfaces);
    }

    /**
     * Return true if the given object is a proxy
     *
     * @param object $object
     * @return bool
     */
    public static function isProxyClass($object)
    {
        return $object instanceof ProxyMark;
    }

    /**
     * Create new proxy object for given class/interface
     * All method calls will be passed to the InvocationHandler
     *
     * @param string|string[] $classOrInterfaces
     * @param InvocationHandler $handler
     * @return object
     */
    public static function newProxyInstance($classOrInterfaces, InvocationHandler $handler)
    {
        $proxyClass = self::getProxyClass($classOrInterfaces);

        return $proxyClass->newInstance($handler);
    }

    /**
     * Return InvocationHandler that is associated to input object
     *
     * @param object $object
     * @return InvocationHandler
     */
    public static function getInvocationHandler($object)
    {
        $reflectionClass = new \ReflectionClass($object);

        $reflectionProperty = $reflectionClass->getProperty('__invocationHandler');
        $reflectionProperty->setAccessible(true);

        return $reflectionProperty->getValue($object);
    }

    /**
     * @return Proxy
     */
    private static function instance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * @param string|string[] $classOrInterfaces
     * @return ProxyClass
     * @throws \ReflectionException
     */
    private function get($classOrInterfaces)
    {
        if (!is_array($classOrInterfaces)) {
            $classOrInterfaces = [$classOrInterfaces];
        }

        $class = $this->extractReflectionClass($classOrInterfaces);
        $interfaces = $this->extractReflectionInterfaces($classOrInterfaces);

        if ($class->isFinal()) {
            throw new ProxyException("Cannot proxy class that is final! ". $class->getName());
        }

        if (count($interfaces) + 1 < count($classOrInterfaces)) {
            throw new ProxyException('Something went wrong :)');
        }

        $proxyClass = $this->proxyClassFactory->get($class, $interfaces);

        return $proxyClass;
    }

    /**
     * @param string[] $classOrInterfaces
     * @return \ReflectionClass
     * @throws \ReflectionException
     */
    private function extractReflectionClass($classOrInterfaces)
    {
        $classes = [];

        foreach ($classOrInterfaces as $name) {
            if (class_exists($name)) {
                $classes[] = new \ReflectionClass($name);
            }
        }

        if (count($classes) > 1) {
            throw new ProxyException('Cannot proxy class with more then 1 base class!');
        }

        if (count($classes) == 1) {
            return reset($classes);
        }

        return new \ReflectionClass(\stdClass::class);
    }

    /**
     * @param $classOrInterfaces
     * @return array
     */
    private function extractReflectionInterfaces($classOrInterfaces)
    {
        $interfaces = [];

        foreach ($classOrInterfaces as $name) {
            if (interface_exists($name)) {
                $interfaces[] = new \ReflectionClass($name);
            }
        }

        return $interfaces;
    }
}