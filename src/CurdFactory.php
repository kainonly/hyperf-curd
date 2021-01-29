<?php
declare(strict_types=1);

namespace Hyperf\Curd;

use Closure;
use Hyperf\Database\Query\Builder;
use Hyperf\DbConnection\Db;
use Hyperf\Utils\Context;
use stdClass;

class CurdFactory
{
    /**
     * 模型名称
     * @var string
     */
    public string $name;

    /**
     * 请求数据
     * @var array
     */
    public array $body;

    /**
     * 错误信息
     * @var array
     */
    private array $error;

    /**
     * 额外条件
     * @var array
     */
    private array $op = [
        'in',
        'not in',
        'between',
        'not between',
        'date',
        'month',
        'day',
        'year',
        'time'
    ];

    /**
     * 条件转换
     * @param array $conditions
     * @return stdClass
     */
    private function convert(array $conditions): stdClass
    {
        $result = new stdClass();
        $result->base = [];
        $result->extend = [];
        foreach ($conditions as $clauses) {
            if (in_array($clauses[1], $this->op, true)) {
                $result->extend[] = $clauses;
            } else {
                $result->base[] = $clauses;
            }
        }
        return $result;
    }

    /**
     * 转换子句
     * @param Builder $query
     * @param array $op
     * @return Builder
     */
    private function clauses(Builder &$query, array $op): Builder
    {
        foreach ($op as $clauses) {
            [$column, $operator, $value] = $clauses;
            switch ($operator) {
                case 'in':
                    $query = $query->whereIn($column, $value);
                    break;
                case 'not in':
                    $query = $query->whereNotIn($column, $value);
                    break;
                case 'between':
                    $query = $query->whereBetween($column, $value);
                    break;
                case 'not between':
                    $query = $query->whereNotBetween($column, $value);
                    break;
                case 'date':
                    $query = $query->whereDate($column, $value);
                    break;
                case 'month':
                    $query = $query->whereMonth($column, $value);
                    break;
                case 'day':
                    $query = $query->whereDay($column, $value);
                    break;
                case 'year':
                    $query = $query->whereYear($column, $value);
                    break;
                case 'time':
                    $query = $query->whereTime($column, $value);
                    break;
            }
        }
        return $query;
    }

    /**
     * 条件
     * @var array
     */
    private array $condition = [];

    /**
     * 设置条件
     * @param array $value
     * @return $this
     */
    public function where(array $value): self
    {
        $this->condition = $value;
        return $this;
    }

    /**
     * 子查询
     * @var Closure|null
     */
    private ?Closure $subQuery = null;

    /**
     * 设置子查询
     * @param Closure $value
     * @return $this
     */
    public function query(Closure $value): self
    {
        $this->subQuery = $value;
        return $this;
    }

    /**
     * 排序
     * @var array
     */
    private array $orders = [];

    /**
     * 设置排序
     * @param array $value
     * @return $this
     */
    public function orderBy(array $value): self
    {
        $this->orders = $value;
        return $this;
    }

    /**
     * 字段
     * @var array
     */
    private array $field;

    /**
     * 设置字段
     * @param array $value
     * @return $this
     */
    public function select(array $value): self
    {
        $this->field = $value;
        return $this;
    }

    /**
     * 生成时间戳
     * @var bool
     */
    private bool $timestamp = true;

    /**
     * 创建时间字段
     * @var string
     */
    private string $createAt = 'create_time';

    /**
     * 更新时间字段
     * @var string
     */
    private string $updateAt = 'update_time';

    /**
     * 设置自动生成时间戳
     * @param bool $value
     * @param string|null $createAt
     * @param string|null $updateAt
     * @return $this
     */
    public function autoTimestamp(bool $value, ?string $createAt = null, ?string $updateAt = null): self
    {
        $this->timestamp = $value;
        if (!empty($createAt)) {
            $this->createAt = $createAt;
        }
        if (!empty($updateAt)) {
            $this->updateAt = $updateAt;
        }
        return $this;
    }

    /**
     * 后置闭包
     * @var Closure|null
     */
    private ?Closure $after = null;

    /**
     * 设置后置处理
     * @param Closure $value
     * @return $this
     */
    public function afterHook(Closure $value): self
    {
        $this->after = $value;
        return $this;
    }

    /**
     * 事务准备闭包
     * @var Closure|null
     */
    protected ?Closure $prep = null;

    /**
     * 监听事务准备
     * @param Closure $value
     * @return $this
     */
    public function prepHook(Closure $value): self
    {
        $this->prep = $value;
        return $this;
    }

    /**
     * 统一处理
     * @param array $condition 条件
     * @return Builder
     */
    private function unifyQuery(array $condition): Builder
    {
        $convert = $this->convert($condition);
        $query = DB::table($this->name)->where($convert->base);
        if (!empty($convert->extend)) {
            $query = $this->clauses($query, $convert->extend);
        }
        if (!empty($this->subQuery)) {
            $query = $query->where($this->subQuery);
        }
        foreach ($this->orders as $key => $value) {
            $query = $query->orderBy($key, $value);
        }
        if (!empty($this->field)) {
            $query = $query->select($this->field);
        }
        return $query;
    }

    /**
     * 获取列表数据
     * @return array
     */
    public function originLists(): array
    {
        $condition = [
            ...$this->condition,
            ...$this->body['where'] ?? []
        ];
        $query = $this->unifyQuery($condition);
        $lists = $query->get();
        return [
            'error' => 0,
            'data' => $lists
        ];
    }

    /**
     * 获取分页数据
     * @return array
     */
    public function lists(): array
    {
        $condition = [
            ...$this->condition,
            ...$this->body['where'] ?? []
        ];
        $convert = $this->convert($condition);
        $totalQuery = Db::table($this->name)->where($convert->base);
        if (!empty($convert->extend)) {
            $totalQuery = $this->clauses($totalQuery, $convert->extend);
        }
        if (!empty($this->subQuery)) {
            $totalQuery = $totalQuery->where($this->subQuery);
        }
        $total = $totalQuery->count();

        $listsQuery = $this->unifyQuery($condition);
        $lists = $listsQuery
            ->forPage($this->body['page']['index'], $this->body['page']['limit'])
            ->get();

        return [
            'error' => 0,
            'data' => [
                'lists' => $lists,
                'total' => $total
            ]
        ];
    }

    /**
     * 获取数据
     * @return array
     */
    public function get(): array
    {
        $condition = [
            ...$this->condition,
            ...!empty($this->body['id']) ? [['id', '=', $this->body['id']]] : $this->body['where']
        ];
        $query = $this->unifyQuery($condition);
        $data = $query->first();
        return [
            'error' => 0,
            'data' => $data
        ];
    }

    /**
     * 新增数据
     * @return array
     */
    public function add(): array
    {
        if ($this->timestamp) {
            $this->body[$this->createAt] = $this->body[$this->updateAt] = time();
        }

        $result = null;
        if (empty($this->after)) {
            $result = Db::table($this->name)->insert($this->body);
        } else {
            $result = Db::transaction(function () {
                $id = null;
                if (!empty($this->body['id'])) {
                    $id = $this->body['id'];
                    $result = Db::table($this->name)->insert($this->body);
                    if (!$result) {
                        return false;
                    }
                } else {
                    $id = Db::table($this->name)->insertGetId($this->body);
                }

                if (empty($id)) {
                    $this->error = [
                        'error' => 1,
                        'msg' => 'this [id] is empty'
                    ];
                    Db::rollBack();
                    return false;
                }

                $param = new stdClass();
                $param->id = $id;
                $func = $this->after;
                if (!$func($param)) {
                    $this->error = (array)Context::get('error', [
                        'error' => 1,
                        'msg' => 'after hook failed'
                    ]);
                    Db::rollBack();
                    return false;
                }

                return true;
            });
        }

        return !$result ? $this->error : [
            'error' => 0,
            'msg' => 'ok'
        ];
    }

    /**
     * 编辑数据
     * @return array
     */
    public function edit(): array
    {
        unset($this->body['switch']);
        if ($this->timestamp) {
            $this->body[$this->updateAt] = time();
        }

        return !Db::transaction(function () {
            $condition = [
                ...$this->condition,
                ...!empty($this->body['id']) ? [['id', '=', $this->body['id']]] : $this->body['where']
            ];
            unset($this->body['where']);
            $convert = $this->convert($condition);
            $query = Db::table($this->name)->where($convert->base);
            if (!empty($convert->extend)) {
                $query = $this->clauses($query, $convert->extend);
            }
            $query->update($this->body);
            if (!empty($this->after)) {
                $func = $this->after;
                if (!$func()) {
                    $this->error = (array)Context::get('error', [
                        'error' => 1,
                        'msg' => 'after hook failed'
                    ]);
                    Db::rollBack();
                    return false;
                }
            }
            return true;
        }) ? $this->error : [
            'error' => 0,
            'msg' => 'ok'
        ];
    }

    /**
     * 删除数据
     * @return array
     */
    public function delete(): array
    {
        return !Db::transaction(function () {
            if (!empty($this->prep)) {
                $func = $this->prep;
                if (!$func()) {
                    $this->error = (array)Context::get('error', [
                        'error' => 1,
                        'msg' => 'prep hook failed'
                    ]);
                    Db::rollBack();
                    return false;
                }
            }

            $convert = $this->convert($this->condition);
            if (!empty($this->body['id'])) {
                $query = Db::table($this->name)
                    ->whereIn('id', $this->body['id'])
                    ->where($convert->base);
            } else {
                $query = Db::table($this->name)
                    ->where($this->body['where'])
                    ->where($convert->base);
            }

            if (!empty($convert->extend)) {
                $query = $this->clauses($query, $convert->extend);
            }
            $result = $query->delete();

            if (!$result) {
                Db::rollBack();
                return false;
            }

            if (!empty($this->after)) {
                $func = $this->after;
                if (!$func()) {
                    $this->error = (array)Context::get('error', [
                        'error' => 1,
                        'msg' => 'after hook failed'
                    ]);
                    Db::rollBack();
                    return false;
                }
            }

            return true;
        }) ? $this->error : [
            'error' => 0,
            'msg' => 'ok'
        ];
    }
}