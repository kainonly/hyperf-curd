<?php
declare(strict_types=1);

namespace Hyperf\Curd\Lifecycle;

use stdClass;

interface AddBeforeHook
{
    public function addBeforeHook(stdClass $ctx): bool;
}