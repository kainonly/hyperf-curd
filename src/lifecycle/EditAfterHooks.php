<?php

namespace Hyperf\Curd\Lifecycle;

interface EditAfterHooks
{
    /**
     * Modify post processing
     * @return mixed
     */
    public function __editAfterHooks();
}
