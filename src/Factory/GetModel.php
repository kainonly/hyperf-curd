<?php
declare(strict_types=1);

namespace Hyperf\Curd\Factory;

use Hyperf\DbConnection\Db;

class GetModel
{
    private string $name;
    private array $body;
    private array $condition;
    private array $field;

    public function __construct(string $name, array $body)
    {
        $this->name = $name;
        $this->body = $body;
    }

    /**
     * 设置数组条件
     * @param array $value
     * @return $this
     */
    public function setCondition(array $value): self
    {
        $this->condition = $value;
        return $this;
    }

    /**
     * 设置字段限制
     * @param array $value
     * @return $this
     */
    public function setField(array $value): self
    {
        $this->field = $value;
        return $this;
    }

    /**
     * 执行
     * @return array
     */
    public function result(): array
    {
        $condition = [
            ...$this->condition,
            ...!empty($this->body['id']) ? [['id', '=', $this->body['id']]] : $this->body['where']
        ];

        $data = DB::table($this->name)
            ->where($condition)
            ->first($this->field);

        return [
            'error' => 0,
            'data' => $data
        ];
    }
}