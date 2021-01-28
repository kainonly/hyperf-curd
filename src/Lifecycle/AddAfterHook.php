<?php
declare(strict_types=1);

namespace Hyperf\Curd\Lifecycle;

use stdClass;

interface AddAfterHook
{
    public function addAfterHook(stdClass $ctx): bool;
}