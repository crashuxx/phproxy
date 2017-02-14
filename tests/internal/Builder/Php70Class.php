<?php

namespace Reflection\internal\Builder;


use Iterator;
use stdClass;

class Php70Class
{
    public function returnTypeMethod(): stdClass
    {
        return (object)[];
    }

    public function classReturnTypeMethod(): Php70Class
    {
        return null;
    }

    public function typedParameter(string $string, Iterator $iterator)
    {
    }
}