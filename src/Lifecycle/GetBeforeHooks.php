<?php
declare(strict_types=1);

namespace Hyperf\Curd\Lifecycle;

interface GetBeforeHooks
{
    /**
     * Get pre-processing of individual data
     * @return boolean
     */
    public function __getBeforeHooks();
}
