<?php

namespace hyperf\curd\lifecycle;

interface OriginListsBeforeHooks
{
    /**
     * List data acquisition preprocessing
     * @return boolean
     */
    public function __originListsBeforeHooks();
}
