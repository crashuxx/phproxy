<?php

namespace Reflection\internal;


use Reflection\ProxyClass;
use ReflectionClass;

class CachedProxyClassFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ProxyClassFactory
     */
    private $cachedProxyClassFactory;

    protected function setUp()
    {
        $this->cachedProxyClassFactory = new CachedProxyClassFactory(new StubProxyClassFactory());
    }

    public function test()
    {
        $proxyStdClass1 = $this->cachedProxyClassFactory->get(new ReflectionClass(\stdClass::class), []);
        $this->assertInstanceOf(ProxyClass::class, $proxyStdClass1);

        $proxyStdClass2 = $this->cachedProxyClassFactory->get(new ReflectionClass(\stdClass::class), []);
        $this->assertInstanceOf(ProxyClass::class, $proxyStdClass2);
        $this->assertEquals($proxyStdClass1, $proxyStdClass2);

        $proxySoapClient = $this->cachedProxyClassFactory->get(new ReflectionClass(\SoapClient::class), []);
        $this->assertInstanceOf(ProxyClass::class, $proxySoapClient);
        $this->assertEquals($proxyStdClass1, $proxySoapClient);
    }
}
