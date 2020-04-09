<?php
declare(strict_types=1);

namespace Hyperf\Curd\Factory;

use Hyperf\DbConnection\Db;

class GetModel extends BaseModel
{
    /**
     * 条件数组
     * @var array
     */
    private array $condition = [];
    /**
     * 限定字段
     * @var array
     */
    private array $field = ['*'];

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

        $convert = $this->convertConditions($condition);

        $query = DB::table($this->name)
            ->where($convert->simple);

        if (!empty($convert->additional)) {
            $query = $this->autoAdditionalClauses(
                $query,
                $convert->additional
            );
        }

        $data = $query->first($this->field);

        return [
            'error' => 0,
            'data' => $data
        ];
    }
}