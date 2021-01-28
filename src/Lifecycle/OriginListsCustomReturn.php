<?php
declare(strict_types=1);

namespace Hyperf\Curd\Lifecycle;

interface OriginListsCustomReturn
{
    public function originListsCustomReturn(array $result): array;
}