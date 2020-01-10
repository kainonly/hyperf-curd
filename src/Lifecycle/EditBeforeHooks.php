<?php
declare(strict_types=1);

namespace Hyperf\Curd\Lifecycle;

interface EditBeforeHooks
{
    /**
     * Modify preprocessing
     * @return bool
     */
    public function __editBeforeHooks(): bool;
}
