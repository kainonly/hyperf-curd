<?php
declare(strict_types=1);

namespace Hyperf\Curd\Common;

class CovertResult
{
    /**
     * 简易条件
     * @var array
     */
    private array $simple;
    /**
     * 附加条件
     * @var array
     */
    private array $additional;

    /**
     * CovertResult constructor.
     * @param array $simple
     * @param array $additional
     */
    public function __construct(array $simple, array $additional)
    {
        $this->simple = $simple;
        $this->additional = $additional;
    }

    /**
     * @return array
     */
    public function getSimple(): array
    {
        return $this->simple;
    }

    /**
     * @return bool
     */
    public function isEmptyAdditional(): bool
    {
        return empty($this->additional);
    }

    /**
     * @return array
     */
    public function getAdditional(): array
    {
        return $this->additional;
    }

}