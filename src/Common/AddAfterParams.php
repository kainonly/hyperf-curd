<?php
declare(strict_types=1);

namespace Hyperf\Curd\Common;

class AddAfterParams
{
    private int $id;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return AddAfterParams
     */
    public function setId(int $id): AddAfterParams
    {
        $this->id = $id;
        return $this;
    }
}