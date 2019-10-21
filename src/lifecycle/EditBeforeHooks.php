<?php

namespace hyperf\curd\lifecycle;

interface EditBeforeHooks
{
    /**
     * Modify preprocessing
     * @return boolean
     */
    public function __editBeforeHooks();
}
