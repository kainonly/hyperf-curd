<?php
declare(strict_types=1);

namespace Hyperf\Curd\Lifecycle;

use stdClass;

interface DeletePrepHook
{
    public function deletePrepHook(stdClass $ctx): bool;
}