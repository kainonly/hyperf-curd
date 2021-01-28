<?php
declare(strict_types=1);

namespace Hyperf\Curd\Lifecycle;

interface ListsCustomReturn
{
    public function listsCustomReturn(array $result): array;
}