<?php
declare(strict_types=1);

namespace Hyperf\Curd\Factory;

use Closure;
use Hyperf\DbConnection\Db;

class ListsModel extends BaseModel
{
    /**
     * 条件数组
     * @var array
     */
    private array $condition = [];
    /**
     * 子查询闭包
     * @var Closure|null
     */
    private ?Closure $subQuery = null;
    /**
     * 排序
     * @var array
     */
    private array $order = [];
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

        $convert = $this->convertConditions($condition);

        $totalQuery = Db::table($this->name)
            ->where($convert->getSimple());

        if (!$convert->isEmptyAdditional()) {
            $totalQuery = $this->autoAdditionalClauses(
                $totalQuery,
                $convert->getAdditional()
            );
        }

        if (!empty($this->subQuery)) {
            $totalQuery = $totalQuery->where($this->subQuery);
        }

        $total = $totalQuery->count();

        $listsQuery = Db::table($this->name)
            ->where($convert->getSimple());

        if (!$convert->isEmptyAdditional()) {
            $listsQuery = $this->autoAdditionalClauses(
                $listsQuery,
                $convert->getAdditional()
            );
        }

        if (!empty($this->order)) {
            $listsQuery = $listsQuery->orderBy(...$this->order);
        }

        if (!empty($this->subQuery)) {
            $listsQuery = $listsQuery->where($this->subQuery);
        }

        $lists = $listsQuery
            ->forPage($this->body['page']['index'], $this->body['page']['limit'])
            ->get($this->field);

        return [
            'error' => 0,
            'data' => [
                'lists' => $lists,
                'total' => $total
            ]
        ];
    }
}