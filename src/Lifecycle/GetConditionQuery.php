<?php
declare(strict_types=1);

namespace Hyperf\Curd\Lifecycle;

use Closure;

interface GetConditionQuery
{
    public function getConditionQuery(array $body): Closure;
}