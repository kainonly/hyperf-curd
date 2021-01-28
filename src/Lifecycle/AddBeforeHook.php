<?php

namespace Hyperf\Curd\Lifecycle;

interface AddBeforeHook
{
    public function addBeforeHook(array &$body): bool;
}