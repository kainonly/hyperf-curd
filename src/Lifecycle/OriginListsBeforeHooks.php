<?php
declare(strict_types=1);

namespace Hyperf\Curd\Lifecycle;

interface OriginListsBeforeHooks
{
    /**
     * List data acquisition preprocessing
     * @return bool
     */
    public function originListsBeforeHooks(): bool;
}
