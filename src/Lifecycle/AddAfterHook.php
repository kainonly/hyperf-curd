<?php

namespace Hyperf\Curd\Lifecycle;

use stdClass;

interface AddAfterHook
{
    public function addAfterHook(array &$body, stdClass $param): bool;
}