<?php
declare(strict_types=1);

namespace Hyperf\Curd\Lifecycle;

interface EditBeforeHooks
{
    /**
     * Modify preprocessing
     * @return boolean
     */
    public function __editBeforeHooks();
}
