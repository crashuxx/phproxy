<?php

namespace Reflection\internal;


interface Evaluator
{
    /**
     * Evaluates code
     *
     * @param $code
     */
    public function evaluate($code);
} 