<?php
declare(strict_types=1);

namespace Hyperf\Curd\Lifecycle;

interface ListsBeforeHooks
{
    /**
     * Paging data acquisition preprocessing
     * @return boolean
     */
    public function __listsBeforeHooks();
}
