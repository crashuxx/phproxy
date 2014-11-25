<?php

namespace Reflection\internal;


use Reflection\internal\Builder\BuilderFactoryStub;
use Reflection\ProxyClass;

class ProxyClassFactoryImplTest extends \PHPUnit_Framework_TestCase
{
    public function testCreateForStdClass()
    {
        $enhancedClassFactory = new ProxyClassFactoryImpl(new BuilderFactoryStub());
        $enhanceClass = new \ReflectionClass(\stdClass::class);
        $interfaces = [];

        $enhancedClass = $enhancedClassFactory->get($enhanceClass, $interfaces);

        $this->assertInstanceOf(ProxyClass::class, $enhancedClass);
    }

    public function testCreateForSoapClient()
    {
        $enhancedClassFactory = new ProxyClassFactoryImpl(new BuilderFactoryStub());
        $enhanceClass = new \ReflectionClass(\SoapClient::class);
        $interfaces = [];

        $enhancedClass = $enhancedClassFactory->get($enhanceClass, $interfaces);

        $this->assertInstanceOf(ProxyClass::class, $enhancedClass);
    }

    public function testCreateForStdClassWithAdditionalInterface()
    {
        $enhancedClassFactory = new ProxyClassFactoryImpl(new BuilderFactoryStub());
        $enhanceClass = new \ReflectionClass(\stdClass::class);
        $interfaces = [new \ReflectionClass(\Iterator::class)];

        $enhancedClass = $enhancedClassFactory->get($enhanceClass, $interfaces);

        $this->assertInstanceOf(ProxyClass::class, $enhancedClass);
    }

    public function testCreateForSoapClientWithAdditionalInterface()
    {
        $enhancedClassFactory = new ProxyClassFactoryImpl(new BuilderFactoryStub());
        $enhanceClass = new \ReflectionClass(\stdClass::class);
        $interfaces = [new \ReflectionClass(\Iterator::class)];

        $enhancedClass = $enhancedClassFactory->get($enhanceClass, $interfaces);

        $this->assertInstanceOf(ProxyClass::class, $enhancedClass);
    }
}
