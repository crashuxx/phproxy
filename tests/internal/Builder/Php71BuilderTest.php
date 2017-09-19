<?php

namespace Reflection\internal\Builder;

use Reflection\internal\ProxyMark;

class Php71BuilderTest extends \PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        parent::setUp();
        if (PHP_MAJOR_VERSION < 7 ||
            PHP_MAJOR_VERSION == 7 && PHP_MINOR_VERSION < 1) {
            $this->markTestSkipped('requires php 7');
        }
    }

    public function testStdClassWithoutInterface()
    {
        $builder = new Php71Builder();
        $newName = uniqid('stdClassBuild');

        $builder->writeClass($newName, 'stdClass', []);
        $builder->writeClose();

        eval($builder->build());

        $this->assertTrue(class_exists($newName, false));
        $this->assertInstanceOf(\stdClass::class, new $newName());
    }

    public function testStdClassWithInterface()
    {
        $builder = new Php71Builder();
        $newName = uniqid('stdClassBuild');

        $builder->writeClass($newName, 'stdClass', [ProxyMark::class]);
        $builder->writeClose();

        eval($builder->build());

        $this->assertTrue(class_exists($newName, false));
        $this->assertInstanceOf(ProxyMark::class, new $newName());
    }

    public function testImplementMethodFromInterface()
    {
        $builder = new Php71Builder();
        $newName = uniqid('stdClassBuild');
        $reflectionClass = new \ReflectionClass(\IteratorAggregate::class);

        $builder->writeClass($newName, 'stdClass', [\IteratorAggregate::class]);
        $builder->writeMethod($reflectionClass->getMethod('getIterator'));
        $builder->writeClose();

        eval($builder->build());

        $this->assertTrue(class_exists($newName, false));
        $this->assertInstanceOf(\IteratorAggregate::class, new $newName());
    }

    /**
     * @test
     * @dataProvider newMethodFeatures
     */
    public function testImplementPhp71Feature($class, $method)
    {
        $builder = new Php71Builder();
        $newName = uniqid('EnhancedClass');
        $reflectionClass = new \ReflectionClass($class);

        $builder->writeClass($newName, $class, []);
        $builder->writeMethod($reflectionClass->getMethod($method));
        $builder->writeClose();
        $generatedCode = $builder->build();

        eval($generatedCode);

        $this->assertTrue(class_exists($newName, false));
        $this->assertInstanceOf($class, new $newName());
    }

    public static function newMethodFeatures()
    {
        return [
            [Php71Class::class, 'returnTypeMethod'],
            [Php71Class::class, 'typedParameter'],
            [Php71Class::class, 'classReturnTypeMethod'],
            [Php71Class::class, 'voidReturnType'],
            [Php71Class::class, 'iterableReturnType'],
            [Php71Class::class, 'iterableParameter']
        ];
    }
}
