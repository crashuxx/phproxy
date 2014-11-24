<?php

namespace Reflection;


use Reflection\fixture\ClassWithMethodParameterDefaultConst;
use Reflection\fixture\ClassWithMethodParameterObjectTyped;
use Reflection\fixture\DummyInterface;
use Reflection\fixture\FinalClass;
use Reflection\fixture\HasConstructor;
use Reflection\internal\ProxyMark;
use Reflection\InvocationHandler\BlankInvocationHandler;
use Reflection\InvocationHandler\DummyInvocationHandler;

class ProxyTest extends \PHPUnit_Framework_TestCase
{
    public function testGet_StdClass()
    {
        $proxyClass = Proxy::getProxyClass(\stdClass::class);

        $this->assertInstanceOf(ProxyClass::class, $proxyClass);
    }

    public function testNewInstanceOf_StdClass()
    {
        $proxyClass = Proxy::newInstance(\stdClass::class, new DummyInvocationHandler());

        $this->assertInstanceOf(ProxyMark::class, $proxyClass);
    }

    /**
     * @expectedException \RuntimeException
     * @expectedExceptionMessage Cannot call method on dummy invocation handler!
     */
    public function testCallMethodOnInstanceOf_StdClass_With_DummyInvocationHandler()
    {
        $proxyClass = Proxy::newInstance(\stdClass::class, new DummyInvocationHandler());
        /** @noinspection PhpUndefinedMethodInspection */
        $proxyClass->NotExistingMethod();
    }

    public function testCallMethodOnInstanceOf_StdClass_With_BlankInvocationHandler()
    {
        $proxyClass = Proxy::newInstance(\stdClass::class, new BlankInvocationHandler());
        /** @noinspection PhpUndefinedMethodInspection */
        $result = $proxyClass->NotExistingMethod();

        $this->assertNull($result);
    }

    public function testGet_ClassWithMethodParameterDefaultConst()
    {
        $proxyClass = Proxy::getProxyClass(ClassWithMethodParameterDefaultConst::class);
        $this->assertInstanceOf(ProxyClass::class, $proxyClass);
    }

    public function testGet_ClassWithMethodParameterObjectTyped()
    {
        $proxyClass = Proxy::getProxyClass(ClassWithMethodParameterObjectTyped::class);
        $this->assertInstanceOf(ProxyClass::class, $proxyClass);
    }

    public function testGet_ClassWithMethodParameterObjectTypedOptional()
    {
        $proxyClass = Proxy::getProxyClass(ClassWithMethodParameterDefaultConst::class);
        $this->assertInstanceOf(ProxyClass::class, $proxyClass);
    }

    /**
     * @expectedException \RuntimeException
     * @expectedExceptionMessage Cannot call method on dummy invocation handler!
     */
    public function testCallMethod_On_ClassWithMethodParameterDefaultConst()
    {
        $proxedClass = Proxy::newInstance(ClassWithMethodParameterDefaultConst::class, new DummyInvocationHandler());

        $proxedClass->asd(new \ArrayIterator([]));
    }

    /**
     * @expectedException \RuntimeException
     * @expectedExceptionMessage Cannot call method on dummy invocation handler!
     */
    public function testCallMethod_On_ClassWithMethodParameterObjectTyped()
    {
        $proxedClass = Proxy::newInstance(ClassWithMethodParameterObjectTyped::class, new DummyInvocationHandler());

        $proxedClass->asd(new \ArrayIterator([]));
    }

    /**
     * @expectedException \RuntimeException
     * @expectedExceptionMessage Cannot call method on dummy invocation handler!
     */
    public function testCallMethod_On_ClassWithMethodParameterObjectTypedOptional()
    {
        $proxedClass = Proxy::newInstance(ClassWithMethodParameterDefaultConst::class, new DummyInvocationHandler());

        $proxedClass->asd(new \ArrayIterator([]));
    }

    public function testCallParentConstructor_On_HasConstructorClass()
    {
        $proxyClass = Proxy::getProxyClass(HasConstructor::class);
        $newInstance = $proxyClass->newInstance(new DummyInvocationHandler());

        $proxyClass->getParentClass()->getConstructor()->invoke($newInstance, 'test value');
    }

    public function testCallParentMethod_On_HasConstructorClass()
    {
        $proxyClass = Proxy::getProxyClass(HasConstructor::class);
        $newInstance = $proxyClass->newInstance(new DummyInvocationHandler());

        $proxyClass->getParentClass()->getConstructor()->invoke($newInstance, 'test value');

        $this->assertEquals('test value', $proxyClass->getParentClass()->getMethod('getValue')->invoke($newInstance));
    }

    /**
     * @expectedException \RuntimeException
     * @expectedExceptionMessage Cannot call method on dummy invocation handler!
     */
    public function testCallMethod_On_HasConstructorClass()
    {
        $proxyClass = Proxy::getProxyClass(HasConstructor::class);
        $newInstance = $proxyClass->newInstance(new DummyInvocationHandler());

        /** @var HasConstructor $newInstance */
        $newInstance->getValue();
    }

    public function testCallToString_On_StdClass()
    {
        $newInstance = Proxy::newInstance(\stdClass::class, new BlankInvocationHandler());

        $this->assertEquals('', (string)$newInstance);
    }

    public function testisProxyClass()
    {
        $this->assertFalse(Proxy::isProxyClass(null));
        $this->assertFalse(Proxy::isProxyClass(true));
        $this->assertFalse(Proxy::isProxyClass('string'));
        $this->assertFalse(Proxy::isProxyClass(new \stdClass));

        $this->assertTrue(Proxy::isProxyClass(Proxy::newInstance(\stdClass::class, new DummyInvocationHandler())));
    }

    public function testGetInvocationHandler()
    {
        $handler = new DummyInvocationHandler();
        $proxyClass = Proxy::newInstance(\stdClass::class, $handler);

        $invocationHandler = Proxy::getInvocationHandler($proxyClass);

        $this->assertEquals($handler, $invocationHandler);
    }

    /**
     * @expectedException \ReflectionException
     */
    public function testGetInvocationHandlerFromNotProxyObject()
    {
        $object = \stdClass::class;
        Proxy::getInvocationHandler($object);
    }

    /**
     * @expectedException \ReflectionException
     */
    public function testGetInvocationHandlerFromString()
    {
        Proxy::getInvocationHandler('string');
    }

    /**
     * @test
     * @expectedException \Reflection\ProxyException
     */
    public function final_class_should_fail()
    {
        Proxy::getProxyClass(FinalClass::class);
    }

    /**
     * @test
     */
    public function should_implements_interfaces()
    {
        $instance = Proxy::newInstance(DummyInterface::class, new DummyInvocationHandler());

        $this->assertInstanceOf(ProxyMark::class, $instance);
        $this->assertInstanceOf(DummyInterface::class, $instance);
    }
}
