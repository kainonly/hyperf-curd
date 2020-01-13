<?php
declare(strict_types=1);

namespace Hyperf\Curd\Lifecycle;

interface DeleteBeforeHooks
{
    /**
     * Delete pre-processing
     * @return bool
     */
    public function deleteBeforeHooks(): bool;
}
