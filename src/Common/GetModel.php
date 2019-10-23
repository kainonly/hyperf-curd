<?php
declare(strict_types=1);

namespace Hyperf\Curd\Common;

use Hyperf\DbConnection\Db;
use Hyperf\HttpServer\Annotation\PostMapping;
use Hyperf\Validation\Contract\ValidatorFactoryInterface;

/**
 * Trait GetModel
 * @package Hyperf\Curd\Common
 * @property ValidatorFactoryInterface $validation
 * @property string $model
 * @property array $post
 * @property array $get_validate
 * @property array $get_default_validate
 * @property array $get_before_result
 * @property array $get_condition
 * @property array $get_field
 */
trait GetModel
{
    /**
     * @return array
     * @PostMapping(path="get")
     */
    public function get()
    {
        try {
            $validator = $this->validation->make($this->post, array_merge(
                $this->get_validate,
                $this->get_default_validate
            ));

            if ($validator->fails()) {
                return [
                    'error' => 1,
                    'msg' => $validator->errors()
                ];
            }

            if (method_exists($this, '__getBeforeHooks') &&
                !$this->__getBeforeHooks()) {
                return $this->get_before_result;
            }

            $condition = $this->get_condition;
            if (isset($this->post['id'])) {
                array_push(
                    $condition,
                    ['id', '=', $this->post['id']]
                );
            } else {
                $condition = array_merge(
                    $condition,
                    $this->post['where']
                );
            }

            $data = DB::table($this->model)
                ->where($condition)
                ->first($this->get_field);

            return method_exists($this, '__getCustomReturn') ?
                $this->__getCustomReturn($data) : [
                    'error' => 0,
                    'data' => $data
                ];
        } catch (\Exception $e) {
            return [
                'error' => 1,
                'msg' => $e->getMessage()
            ];
        }
    }
}