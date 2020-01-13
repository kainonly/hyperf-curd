<?php
declare(strict_types=1);

namespace Hyperf\Curd\Lifecycle;

interface ListsCustom
{
    /**
     * Custom paged data return
     * @param array $lists
     * @param int $total
     * @return array
     */
    public function listsCustomReturn(array $lists, int $total): array;
}
