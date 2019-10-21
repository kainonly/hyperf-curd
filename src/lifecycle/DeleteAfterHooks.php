<?php

namespace hyperf\curd\lifecycle;

interface DeleteAfterHooks
{
    /**
     * Delete post processing
     * @return mixed
     */
    public function __deleteAfterHooks();
}
