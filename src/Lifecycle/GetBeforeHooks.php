<?php

namespace Hyperf\Curd\Lifecycle;

interface GetBeforeHooks
{
    /**
     * Get pre-processing of individual data
     * @return boolean
     */
    public function __getBeforeHooks();
}
