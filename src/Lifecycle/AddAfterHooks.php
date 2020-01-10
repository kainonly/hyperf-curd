<?php
declare(strict_types=1);

namespace Hyperf\Curd\Lifecycle;

interface AddAfterHooks
{
    /**
     * Add post processing
     * @param string|int $id
     * @return mixed
     */
    public function __addAfterHooks($id);
}
