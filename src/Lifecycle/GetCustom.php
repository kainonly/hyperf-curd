<?php
declare(strict_types=1);

namespace Hyperf\Curd\Lifecycle;

interface GetCustom
{
    /**
     * Customize individual data returns
     * @param array $data
     * @return array
     */
    public function getCustomReturn(array $data): array;
}
