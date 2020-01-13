<?php
declare(strict_types=1);

namespace Hyperf\Curd\Lifecycle;

interface DeleteAfterHooks
{
    /**
     * Delete post processing
     * @return bool
     */
    public function deleteAfterHooks(): bool;
}
