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
            if (PHP_MAJOR_VERSION == 7 && PHP_MINOR_VERSION == 0) {
                return new Php70Builder();
            } else {
                return new Php71Builder();
            }
        }
        return new DefaultBuilder();
    }
}