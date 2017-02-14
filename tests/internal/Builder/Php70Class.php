<?php

namespace Reflection\internal\Builder;


use stdClass;

class Php70Class
{
    public function returnTypeMethod(): stdClass
    {
        return (object)[];
    }
}