phproxy
=======

Class proxy for php

Usage:
```php
class MyInvocationHandler implements \Reflection\InvocationHandler\InvocationHandler
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
