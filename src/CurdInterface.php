<?php
declare(strict_types=1);

namespace Hyperf\Curd;

interface CurdInterface
{
    /**
     * 验证请求并返回数据
     * @param array $rule
     * @param mixed ...$extend
     * @return array
     */
    public function should(array $rule = [], ...$extend): array;

    /**
     * 计划 curd
     * @param string $name
     * @param array $body
     * @return CurdFactory
     */
    public function model(string $name, array $body): CurdFactory;
}