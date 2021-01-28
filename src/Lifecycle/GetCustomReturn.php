<?php
declare(strict_types=1);

namespace Hyperf\Curd\Lifecycle;

interface GetCustomReturn
{
    public function getCustomReturn(array $result): array;
}