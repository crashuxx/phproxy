<?php

namespace Reflection\internal\Builder;


class BuilderFactoryStub extends BuilderFactory
{
    /**
     * @return Builder
     */
    public function get()
    {
        return new StubbedBuilder();
    }
}