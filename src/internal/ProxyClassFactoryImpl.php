<?php

namespace Reflection\internal;


use Reflection\internal\Builder\BuilderFactory;
use Reflection\ProxyClass;
use ReflectionMethod;

class ProxyClassFactoryImpl implements ProxyClassFactory
{
    /**
     * @var BuilderFactory
     */
    private $builderFactory;

    /**
     * @var Evaluator
     */
    private $evaluator;

    public function __construct(BuilderFactory $builderFactory, Evaluator $evaluator)
    {
        $this->builderFactory = $builderFactory;
        $this->evaluator = $evaluator;
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

        $implementNames = array_unique(array_merge([ProxyMark::class], $this->extractInterfaceNames($interfaces)));

        $builder->writeNamespace('Reflection\enhanced');
        $builder->writeClass($enhancedClassName, $baseClassName, $implementNames);
        $builder->writeConstructor();

        $reflectionMethods = $this->extractMethods(array_merge([$reflectionClass], $interfaces));
        $methods = array_filter($reflectionMethods, function (ReflectionMethod $method) {
            return !in_array($method->getName(), ['__call', '__toString']);
        });

        foreach ($methods as $reflectionMethod) {
            $builder->writeMethod($reflectionMethod);
        }

        $builder->writeToStringMethod();

        $callMethods = array_filter($reflectionMethods, function (ReflectionMethod $method) {
            return $method->getName() === '__call';
        });
        $callMethod = reset($callMethods);
        $builder->writeCallMethod($callMethod ? $callMethod : null);

        $builder->writeClose();
        $generatedCode = $builder->build();

        $this->evaluator->evaluate($generatedCode);

        return new ProxyClassImpl('\\Reflection\\enhanced\\' . $enhancedClassName, $reflectionClass, $interfaces);
    }

    /**
     * @param \ReflectionClass[] $interfaces
     * @return string[]
     */
    private function extractInterfaceNames($interfaces = [])
    {
        $names = [];

        foreach ($interfaces as $interface) {
            $names[] = $interface->getName();
        }

        return array_unique($names);
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

                $methods[] = $reflectionMethod;
            }
        }

        return $methods;
    }
}