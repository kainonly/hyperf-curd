<?php
declare(strict_types=1);

namespace Hyperf\Curd\Factory;

use Hyperf\Database\Query\Builder;
use stdClass;

abstract class BaseModel
{
    /**
     * 表名称
     * @var string
     */
    protected string $name;
    /**
     * 请求body
     * @var array
     */
    protected array $body;
    /**
     * 额外条件
     * @var array
     */
    private array $additionalOperators = [
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
     * BaseModel constructor.
     * @param string $name
     * @param array $body
     */
    public function __construct(string $name, array $body)
    {
        $this->name = $name;
        $this->body = $body;
    }

    /**
     * 条件转换
     * @param array $conditions
     * @return stdClass
     */
    protected function convertConditions(array $conditions): stdClass
    {
        $additional = [];
        $simple = array_filter($conditions, function ($v) use (&$additional) {
            if (!in_array($v[1], $this->additionalOperators)) {
                array_push($additional, $v);
                return true;
            }
            return false;
        });
        $result = new stdClass();
        $result->simple = $simple;
        $result->additional = $additional;
        return $result;
    }

    /**
     * 自动配置额外条件
     * @param Builder $query
     * @param array $additional
     * @return Builder
     */
    protected function autoAdditionalClauses(Builder &$query, array $additional): Builder
    {
        foreach ($additional as $clauses) {
            list($column, $operator, $value) = $clauses;
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
}