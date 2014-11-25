<?php

namespace Reflection\internal\Evaluator;

class EvalEvaluatorTest extends \PHPUnit_Framework_TestCase
{
    public function testEvaluate()
    {
        $evaluator = new EvalEvaluator();

        $code = 'echo "test";';

        $evaluator->evaluate($code);

        $this->expectOutputString("test");
    }
} 