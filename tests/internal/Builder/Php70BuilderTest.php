<?php

namespace Reflection\internal\Builder;


use Reflection\internal\ProxyMark;

class Php70BuilderTest extends \PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        parent::setUp();
        if (PHP_MAJOR_VERSION < 7) {
            $this->markTestSkipped('requires php 7');
        }
    }

    public function testStdClassWithoutInterface()
    {
        $builder = new Php70Builder();
        $newName = uniqid('stdClassBuild');

        $builder->writeClass($newName, 'stdClass', []);
        $builder->writeClose();

        eval($builder->build());

        $this->assertTrue(class_exists($newName, false));
        $this->assertInstanceOf(\stdClass::class, new $newName());
    }

    public function testStdClassWithInterface()
    {
        $builder = new Php70Builder();
        $newName = uniqid('stdClassBuild');

        $builder->writeClass($newName, 'stdClass', [ProxyMark::class]);
        $builder->writeClose();

        eval($builder->build());

        $this->assertTrue(class_exists($newName, false));
        $this->assertInstanceOf(ProxyMark::class, new $newName());
    }

    public function testImplementMethodFromInterface()
    {
        $builder = new Php70Builder();
        $newName = uniqid('stdClassBuild');
        $reflectionClass = new \ReflectionClass(\IteratorAggregate::class);

        $builder->writeClass($newName, 'stdClass', [\IteratorAggregate::class]);
        $builder->writeMethod($reflectionClass->getMethod('getIterator'));
        $builder->writeClose();

        eval($builder->build());

        $this->assertTrue(class_exists($newName, false));
        $this->assertInstanceOf(\IteratorAggregate::class, new $newName());
    }

    public function testImplementPhp7ReturnTypeMethod()
    {
        $builder = new Php70Builder();
        $newName = uniqid('Php7Class');
        $reflectionClass = new \ReflectionClass(Php70Class::class);

        $builder->writeClass($newName, Php70Class::class, []);
        $builder->writeMethod($reflectionClass->getMethod('returnTypeMethod'));
        $builder->writeClose();

        eval($builder->build());

        $this->assertTrue(class_exists($newName, false));
        $this->assertInstanceOf(Php70Class::class, new $newName());
    }

    public function testImplementPhp7TypedMethodParameter()
    {
        $builder = new Php70Builder();
        $newName = uniqid('Php7Class');
        $reflectionClass = new \ReflectionClass(Php70Class::class);

        $builder->writeClass($newName, Php70Class::class, []);
        $builder->writeMethod($reflectionClass->getMethod('typedParameter'));
        $builder->writeClose();

        eval($builder->build());

        $this->assertTrue(class_exists($newName, false));
        $this->assertInstanceOf(Php70Class::class, new $newName());
    }
}
