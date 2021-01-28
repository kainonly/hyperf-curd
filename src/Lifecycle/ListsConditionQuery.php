<?php
declare(strict_types=1);

namespace Hyperf\Curd\Lifecycle;

use Closure;

interface ListsConditionQuery
{
    public function listsConditionQuery(array $body): Closure;
}