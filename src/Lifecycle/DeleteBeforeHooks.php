<?php

namespace Hyperf\Curd\Lifecycle;

interface DeleteBeforeHooks
{
    /**
     * Delete pre-processing
     * @return boolean
     */
    public function __deleteBeforeHooks();
}
