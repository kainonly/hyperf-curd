<?php
declare(strict_types=1);

namespace Hyperf\Curd\Common;

class DeleteAfterParams
{
    private array $id;

    /**
     * @return array
     */
    public function getId(): array
    {
        return $this->id;
    }

    /**
     * @param array $id
     * @return DeleteAfterParams
     */
    public function setId(array $id): DeleteAfterParams
    {
        $this->id = $id;
        return $this;
    }
}