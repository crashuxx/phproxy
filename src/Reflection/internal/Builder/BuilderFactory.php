<?php

namespace Reflection\internal\Builder;


class BuilderFactory
{
    /**
     * @return Builder
     */
    public function get()
    {
        return new DefaultBuilder();
    }
}