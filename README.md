phproxy
=======

Class proxy for php based on [java.lang.reflect.Proxy](https://docs.oracle.com/javase/7/docs/api/java/lang/reflect/Proxy.html).
This library is under [Apache License 2.0](http://www.apache.org/licenses/LICENSE-2.0.html).

[![Build Status](https://travis-ci.org/crashuxx/phproxy.svg?branch=master)](https://travis-ci.org/crashuxx/phproxy)

PHP >= 5.5
HHVM >= 3.4.0

Usage:
```php
class MyInvocationHandler implements \Reflection\InvocationHandler
{
    /**
     * @param object $proxy
     * @param string $method
     * @param mixed[] $args
     * @return mixed
     */
    function invoke($proxy, $method, $args)
    {
        echo $method;
        return 'my return  value';
    }
}

$proxy = \Reflection\Proxy::newProxyInstance(\stdClass::class, new MyInvocationHandler());
echo $proxy->CustomMethod();
```
