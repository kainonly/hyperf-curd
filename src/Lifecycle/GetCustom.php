<?php
declare(strict_types=1);

namespace Hyperf\Curd\Lifecycle;

interface GetCustom
{
    /**
     * Customize individual data returns
     * @param mixed $data
     * @return array
     */
    public function __getCustomReturn($data);
}
