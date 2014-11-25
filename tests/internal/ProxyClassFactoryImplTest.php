<?php

namespace Reflection\internal;


use Reflection\internal\Builder\BuilderFactoryStub;
use Reflection\ProxyClass;

class ProxyClassFactoryImplTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider createDataProvider
     */
    public function testCreate($className)
    {
        $enhancedClassFactory = $this->createEnhancedClassFactory();
        $enhanceClass = new \ReflectionClass($className);

        $enhancedClass = $enhancedClassFactory->get($enhanceClass);

        $this->assertInstanceOf(ProxyClass::class, $enhancedClass);
    }

    public function createDataProvider()
    {
        return [
            [\stdClass::class],
            [\SoapClient::class]
        ];
    }

    /**
     * @dataProvider createWithAdditionalInterfaceDataProvider
     */
    public function testCreateWithAdditionalInterface($className, $interfaceName)
    {
        $enhancedClassFactory = $this->createEnhancedClassFactory();
        $enhanceClass = new \ReflectionClass($className);
        $interfaces = [new \ReflectionClass($interfaceName)];

        $enhancedClass = $enhancedClassFactory->get($enhanceClass, $interfaces);

        $this->assertInstanceOf(ProxyClass::class, $enhancedClass);
    }

    public function createWithAdditionalInterfaceDataProvider()
    {
        return [
            [\stdClass::class, \Iterator::class],
            [\SoapClient::class, \Iterator::class]
        ];
    }

    /**
     * @return ProxyClassFactoryImpl
     */
    private function createEnhancedClassFactory()
    {
        $enhancedClassFactory = new ProxyClassFactoryImpl(new BuilderFactoryStub());

        return $enhancedClassFactory;
    }
}
