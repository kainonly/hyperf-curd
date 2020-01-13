<?php
declare(strict_types=1);

namespace Hyperf\Curd\Lifecycle;

interface EditAfterHooks
{
    /**
     * Modify post processing
     * @return bool
     */
    public function editAfterHooks(): bool;
}
