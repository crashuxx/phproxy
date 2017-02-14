<?php

namespace Reflection\internal\Builder;


class BuilderFactory
{
    /**
     * @return Builder
     */
    public function get()
    {
        if (PHP_MAJOR_VERSION >= 7) {
            return new Php70Builder();
        }
        return new DefaultBuilder();
    }
}