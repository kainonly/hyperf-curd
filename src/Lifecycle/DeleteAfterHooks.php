<?php
declare(strict_types=1);

namespace Hyperf\Curd\Lifecycle;

interface DeleteAfterHooks
{
    /**
     * Delete post processing
     * @return mixed
     */
    public function __deleteAfterHooks();
}
