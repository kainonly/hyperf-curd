<?php
declare(strict_types=1);

namespace Hyperf\Curd\Lifecycle;

use stdClass;

interface DeleteBeforeHook
{
    public function deleteBeforeHook(stdClass $ctx): bool;
}