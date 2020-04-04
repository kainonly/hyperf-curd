<?php
declare(strict_types=1);

namespace Hyperf\Curd\Common;

class EditAfterParams
{
    private int $id;
    private bool $switch;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return EditAfterParams
     */
    public function setId(int $id): EditAfterParams
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return bool
     */
    public function isSwitch(): bool
    {
        return $this->switch;
    }

    /**
     * @param bool $switch
     */
    public function setSwitch(bool $switch): void
    {
        $this->switch = $switch;
    }
}