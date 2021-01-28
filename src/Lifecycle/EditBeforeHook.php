<?php
declare(strict_types=1);

namespace Hyperf\Curd\Lifecycle;

use stdClass;

interface EditBeforeHook
{
    public function editBeforeHook(stdClass $ctx): bool;
}