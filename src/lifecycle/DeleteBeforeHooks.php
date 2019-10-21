<?php

namespace hyperf\curd\lifecycle;

interface DeleteBeforeHooks
{
    /**
     * Delete pre-processing
     * @return boolean
     */
    public function __deleteBeforeHooks();
}
