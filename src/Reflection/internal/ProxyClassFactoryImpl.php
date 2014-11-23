<?php

namespace Reflection\internal;


use Reflection\internal\Builder\BuilderFactory;
use Reflection\ProxyClass;

class ProxyClassFactoryImpl implements ProxyClassFactory
{
    /**
     * @var BuilderFactory
     */
    private $builderFactory;

    public function __construct(BuilderFactory $builderFactory)
    {
        $this->builderFactory = $builderFactory;
    }

    /**
     * @param \ReflectionClass $reflectionClass
     * @param \ReflectionClass[] $interfaces
     * @return ProxyClass
     */
    public function get(\ReflectionClass $reflectionClass, $interfaces = [])
    {
        $builder = $this->builderFactory->get();

        $baseClassName = $reflectionClass->getName();
        $enhancedClassName = $reflectionClass->getShortName() . uniqid('Enhanced');

        $builder->writeNamespace('Reflection\enhanced');
        $builder->writeClass($enhancedClassName, $baseClassName, [ProxyMark::class]);
        $builder->writeConstructor();

        $reflectionMethods = $this->extractMethods([$reflectionClass] + $interfaces);

        foreach ($reflectionMethods as $reflectionMethod) {
            $builder->writeMethod($reflectionMethod);
        }

        $builder->writeToStringMethod();
        $builder->writeCallMethod();

        $builder->writeClose();
        $generatedCode = $builder->build();

        eval($generatedCode);

        return new ProxyClassImpl('\\Reflection\\enhanced\\' . $enhancedClassName, $reflectionClass, $interfaces);
    }

    /**
     * @param \ReflectionClass[] $interfaces
     * @return \ReflectionMethod[]
     */
    private function extractMethods($interfaces = [])
    {
        $methods = [];

        foreach ($interfaces as $interface) {
            foreach ($interface->getMethods() as $reflectionMethod) {
                /** @var \ReflectionMethod $reflectionMethod */
                $modifier = \Reflection::getModifierNames($reflectionMethod->getModifiers());

                if ($reflectionMethod->isConstructor() || $reflectionMethod->isDestructor()) {
                    continue;
                }

                if (in_array('final', $modifier) || in_array('static', $modifier)) {
                    continue;
                }

                if (in_array($reflectionMethod->getName(), ['__call', '__toString'])) {
                    continue;
                }

                $methods[] = $reflectionMethod;
            }
        }

        return $methods;
    }
}