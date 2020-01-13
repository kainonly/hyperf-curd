<?php
declare(strict_types=1);

namespace Hyperf\Curd\Lifecycle;

interface OriginListsCustom
{
    /**
     * Custom list data return
     * @param array $lists
     * @return array
     */
    public function originListsCustomReturn(array $lists): array;
}
