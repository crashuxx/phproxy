<?php

namespace Reflection\internal\Builder;


use ReflectionException;

class Php70Builder implements Builder
{
    private $propertyName;
    private $code = [];

    public function __construct()
    {
        $this->propertyName = '__invocationHandler';
    }

    public function writeNamespace($namespace)
    {
        $this->code[] = 'namespace ' . trim($namespace, " \t\n\r\0\x0B\\") . ';';
    }

    /**
     * @param string $new
     * @param string $baseClass
     * @param string[] $interfaces
     */
    public function writeClass($new, $baseClass, $interfaces)
    {
        $string = 'class ' . $new . ' ';
        if ($baseClass) {
            $string .= 'extends \\' . $baseClass . ' ';
        }

        if (!empty($interfaces)) {
            $string .= 'implements \\' . implode(',\\', $interfaces);
        }

        $this->code[] = $string . ' {';
    }

    public function writeConstructor()
    {
        $this->code[] = '  private $' . $this->propertyName . ';';
        $this->code[] = '  public function __construct(\Reflection\InvocationHandler $invocationHandler) {';
        $this->code[] = '    $this->' . $this->propertyName . ' = $invocationHandler;';
        $this->code[] = '  }';
    }

    public function writeMethod(\ReflectionMethod $method)
    {
        $args = [];
        foreach ($method->getParameters() as $parameter) {
            $arg = '';

            if ($parameter->isArray()) {
                $arg .= 'array ';
            } else if ($parameter->getClass()) {
                $arg .= '\\' . $parameter->getClass()->getName() . ' ';
            } else if ($parameter->hasType()) {
                $arg .= $parameter->getType(). ' ';
            }

            if ($parameter->isPassedByReference()) {
                $arg .= '&';
            }

            $arg .= '$' . $parameter->getName();

            try {
                $defaultValue = $parameter->getDefaultValue();
                $arg .= ' = ' . var_export($defaultValue, true);
            } catch (ReflectionException $e) {
                if ($parameter->isOptional()) {
                    $arg .= ' = null';
                }
            }

            $args[] = $arg;
        }

        $modifiers = array_diff(\Reflection::getModifierNames($method->getModifiers()), ['abstract']);

        $methodName = ($method->returnsReference() ? '&' : '') . $method->getName();

        $this->code[] = '  ' . implode(' ', $modifiers) . ' function ' . $methodName . '(' . implode(', ', $args) . ') ';

        $returnType = $method->getReturnType();
        if ($returnType != null) {
            $this->code[count($this->code) - 1] .= ': ' . $returnType;
        }

        $this->code[count($this->code) - 1] .= ' {';
        $this->code[] = '    $result = $this->' . $this->propertyName . '->invoke($this, \'' . $method->getName() . '\', func_get_args());';
        $this->code[] = '    return $result;';
        $this->code[] = '  }';
    }

    public function writeToStringMethod()
    {
        $this->code[] = '  public function __toString() {';
        $this->code[] = '    $result = $this->' . $this->propertyName . '->invoke($this, \'__toString\', []);';
        $this->code[] = '    return $result;';
        $this->code[] = '}';
    }

    public function writeCallMethod()
    {
        $this->code[] = '  public function __call($name, $args) {';
        $this->code[] = '    $result = $this->' . $this->propertyName . '->invoke($this, $name, $args);';
        $this->code[] = '    return $result;';
        $this->code[] = '  }';
    }

    public function writeClose()
    {
        $this->code[] = '}';
    }

    /**
     * @return string
     */
    public function build()
    {
        return implode("\n", $this->code);
    }
}