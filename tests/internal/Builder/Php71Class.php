<?php

namespace Reflection\internal\Builder;

use Iterator;
use stdClass;

class Php71Class
{
    public function returnTypeMethod(): stdClass
    {
        return (object)[];
    }

    public function classReturnTypeMethod(): Php70Class
    {
        return null;
    }

    public function iterableReturnType(): iterable
    {
        return null;
    }

    public function objectReturnType(): object
    {
        return null;
    }

    public function voidReturnType(): void
    {
    }

    public function typedParameter(string $string, Iterator $iterator)
    {
    }

    public function iterableParameter(iterable $parameter)
    {
    }

    public function objectParameter(object $parameter)
    {
    }
}