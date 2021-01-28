<?php
declare(strict_types=1);

namespace Hyperf\Curd\Lifecycle;

use stdClass;

interface DeleteAfterHook
{
    public function deleteAfterHook(stdClass $ctx): bool;
}