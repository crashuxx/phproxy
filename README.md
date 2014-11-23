phproxy
=======

Class proxy for php [![Build Status](https://travis-ci.org/crashuxx/phproxy.svg?branch=master)](https://travis-ci.org/crashuxx/phproxy)

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

$proxy = \Reflection\Proxy::newInstance(\stdClass::class, new MyInvocationHandler());
echo $proxy->CustomMethod();
```
