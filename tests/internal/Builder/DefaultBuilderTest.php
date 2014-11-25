<?php

namespace Reflection\internal\Builder;


use Reflection\internal\ProxyMark;

class DefaultBuilderTest extends \PHPUnit_Framework_TestCase
{
    public function testStdClassWithoutInterface()
    {
        $builder = new DefaultBuilder();
        $newName = uniqid('stdClassBuild');

        $builder->writeClass($newName, 'stdClass', []);
        $builder->writeClose();

        eval($builder->build());

        $this->assertTrue(class_exists($newName, false));
        $this->assertInstanceOf(\stdClass::class, new $newName());
    }

    public function testStdClassWithInterface()
    {
        $builder = new DefaultBuilder();
        $newName = uniqid('stdClassBuild');

        $builder->writeClass($newName, 'stdClass', [ProxyMark::class]);
        $builder->writeClose();

        eval($builder->build());

        $this->assertTrue(class_exists($newName, false));
        $this->assertInstanceOf(ProxyMark::class, new $newName());
    }

    public function testImplementMethodFromInterface()
    {
        $builder = new DefaultBuilder();
        $newName = uniqid('stdClassBuild');
        $reflectionClass = new \ReflectionClass(\IteratorAggregate::class);

        $builder->writeClass($newName, 'stdClass', [\IteratorAggregate::class]);
        $builder->writeMethod($reflectionClass->getMethod('getIterator'));
        $builder->writeClose();

        eval($builder->build());

        $this->assertTrue(class_exists($newName, false));
        $this->assertInstanceOf(\IteratorAggregate::class, new $newName());
    }
}
