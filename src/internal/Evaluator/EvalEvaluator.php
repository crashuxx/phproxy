<?php

namespace Reflection\internal\Evaluator;

use Reflection\internal\Evaluator;

class EvalEvaluator implements Evaluator
{

    /**
     * Evaluates code
     *
     * @param $code
     */
    public function evaluate($code)
    {
        eval($code);
    }
}
