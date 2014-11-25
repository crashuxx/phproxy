<?php

namespace Reflection\fixture;


class ClassWithMethodParameterDefaultConst
{
    const TEST_CONST = 2;

    public function asd($iterator = self::TEST_CONST)
    {

    }
}