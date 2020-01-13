<?php
declare(strict_types=1);

namespace Hyperf\Curd\Common;

use Closure;
use Exception;
use Hyperf\DbConnection\Db;
use Hyperf\HttpServer\Annotation\PostMapping;
use Hyperf\Validation\Contract\ValidatorFactoryInterface;

/**
 * Trait ListsModel
 * @package Hyperf\Curd\Common
 * @property ValidatorFactoryInterface $validation
 * @property string $model
 * @property array $post
 * @property array $lists_validate
 * @property array $lists_default_validate
 * @property array $lists_before_result
 * @property array $lists_condition
 * @property Closure|null $lists_query
 * @property array $lists_order
 * @property array $lists_field
 */
trait ListsModel
{
    /**
     * @PostMapping()
     * @return array
     */
    public function lists(): array
    {
        try {
            $validator = $this->validation->make($this->post, array_merge(
                $this->lists_validate,
                $this->lists_default_validate
            ));

            if ($validator->fails()) {
                return [
                    'error' => 1,
                    'msg' => $validator->errors()
                ];
            }

            if (method_exists($this, 'listsBeforeHooks') &&
                !$this->listsBeforeHooks()) {
                return $this->lists_before_result;
            }

            $condition = $this->lists_condition;
            if (isset($this->post['where'])) {
                $condition = array_merge(
                    $condition,
                    $this->post['where']
                );
            }

            $totalQuery = DB::table($this->model)
                ->where($condition);

            $total = empty($this->lists_condition_group) ?
                $totalQuery->count() :
                $totalQuery->where($this->lists_condition_group)
                    ->count();

            $listsQuery = DB::table($this->model)
                ->where($condition)
                ->orderBy(...$this->lists_order)
                ->take($this->post['page']['limit'])
                ->skip($this->post['page']['index'] - 1);

            $lists = empty($this->lists_condition_group) ?
                $listsQuery->get($this->lists_field) :
                $listsQuery->where($this->lists_condition_group)
                    ->get($this->lists_field);

            return method_exists($this, 'listsCustomReturn') ?
                $this->listsCustomReturn($lists, $total) : [
                    'error' => 0,
                    'data' => [
                        'lists' => $lists,
                        'total' => $total
                    ]
                ];
        } catch (Exception $exception) {
            return [
                'error' => 1,
                'msg' => $exception->getMessage()
            ];
        }
    }
}
