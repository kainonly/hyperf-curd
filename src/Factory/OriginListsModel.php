<?php
declare(strict_types=1);

namespace Hyperf\Curd\Factory;

use Closure;
use Hyperf\DbConnection\Db;

class OriginListsModel
{
    private string $name;
    private array $body;
    private array $condition = [];
    private ?Closure $subQuery = null;
    private array $order = [];
    private array $field = ['*'];

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
     * 设置子查询
     * @param Closure $value
     * @return $this
     */
    public function setSubQuery(Closure $value): self
    {
        $this->subQuery = $value;
        return $this;
    }

    /**
     * 设置排序
     * @param string $column
     * @param string $direction
     * @return $this
     */
    public function setOrder(string $column, string $direction): self
    {
        $this->order = [$column, $direction];
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
            ...$this->body['where'] ?? []
        ];

        $query = DB::table($this->name)
            ->where($condition);

        if (!empty($this->order)) {
            $query = $query
                ->orderBy(...$this->order);
        }

        if (!empty($this->subQuery)) {
            $query = $query->where($this->subQuery);
        }

        $lists = $query->get($this->field);

        return [
            'error' => 0,
            'data' => $lists
        ];
    }
}