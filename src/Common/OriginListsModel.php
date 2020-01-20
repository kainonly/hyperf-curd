<?php
declare(strict_types=1);

namespace Hyperf\Curd\Common;

use Closure;
use Hyperf\DbConnection\Db;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\Validation\Contract\ValidatorFactoryInterface;

/**
 * Trait ListsModel
 * @package Hyperf\Curd\Common
 * @property RequestInterface $request
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
     * @return array
     */
    public function originLists(): array
    {
        $this->post = $this->request->post();
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

        if (method_exists($this, 'originListsBeforeHooks')
            && !$this->originListsBeforeHooks()) {
            return $this->origin_lists_before_result;
        }

        $condition = [
            ...$this->origin_lists_condition,
            ...($this->post['where'] ?? [])
        ];

        $query = DB::table($this->model)
            ->where($condition);

        if (!empty($this->origin_lists_order) && count($this->origin_lists_order) === 2) {
            $query = $query
                ->orderBy(...$this->origin_lists_order);
        }

        $lists = empty($this->origin_lists_query) ?
            $query->get($this->origin_lists_field) :
            $query->where($this->origin_lists_query);

        return method_exists($this, 'originListsCustomReturn') ?
            $this->originListsCustomReturn($lists) : [
                'error' => 0,
                'data' => $lists
            ];
    }
}
