<?php
declare(strict_types=1);

namespace Hyperf\Curd\Lifecycle;

interface AddAfterHooks
{
    /**
     * Add post processing
     * @param int $id
     * @return bool
     */
    public function __addAfterHooks(int $id): bool;
}
