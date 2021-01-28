<?php
declare(strict_types=1);

namespace Hyperf\Curd\Lifecycle;

use stdClass;

interface EditAfterHook
{
    public function editAfterHook(stdClass $ctx): bool;
}