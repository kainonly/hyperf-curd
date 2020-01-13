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
 * @property array $origin_lists_validate
 * @property array $origin_lists_default_validate
 * @property array $origin_lists_before_result
 * @property array $origin_lists_condition
 * @property Closure|null $origin_lists_query
 * @property array $origin_lists_order
 * @property array $origin_lists_field
 */
trait OriginListsModel
{
    /**
     * @PostMapping()
     * @return array
     */
    public function originLists(): array
    {
        try {
            $validator = $this->validation->make($this->post, array_merge(
                $this->origin_lists_validate,
                $this->origin_lists_default_validate
            ));

            if ($validator->fails()) {
                return [
                    'error' => 1,
                    'msg' => $validator->errors()
                ];
            }

            if (method_exists($this, 'originListsBeforeHooks') &&
                !$this->originListsBeforeHooks()) {
                return $this->origin_lists_before_result;
            }

            $condition = $this->origin_lists_condition;
            if (isset($this->post['where'])) {
                $condition = array_merge(
                    $condition,
                    $this->post['where']
                );
            }

            $listsQuery = DB::table($this->model)
                ->where($condition)
                ->orderBy(...$this->origin_lists_order);

            $lists = empty($this->origin_lists_query) ?
                $listsQuery->get($this->origin_lists_field) :
                $listsQuery->where($this->origin_lists_query)
                    ->get($this->origin_lists_field);

            return method_exists($this, 'originListsCustomReturn') ?
                $this->originListsCustomReturn($lists) : [
                    'error' => 0,
                    'data' => $lists
                ];
        } catch (Exception $exception) {
            return [
                'error' => 1,
                'msg' => $exception->getMessage()
            ];
        }
    }
}
