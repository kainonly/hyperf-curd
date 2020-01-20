<?php
declare(strict_types=1);

namespace Hyperf\Curd\Common;

use Hyperf\DbConnection\Db;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\Validation\Contract\ValidatorFactoryInterface;

/**
 * Trait GetModel
 * @package Hyperf\Curd\Common
 * @property RequestInterface $request
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
     */
    public function get(): array
    {
        $this->post = $this->request->post();
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

        if (method_exists($this, 'getBeforeHooks')
            && !$this->getBeforeHooks()) {
            return $this->get_before_result;
        }

        $condition = [
            ...$this->get_condition,
            ...!empty($this->post['id']) ? [['id', '=', $this->post['id']]] : $this->post['where']
        ];

        $data = DB::table($this->model)
            ->where($condition)
            ->first($this->get_field);

        return method_exists($this, 'getCustomReturn') ?
            $this->getCustomReturn($data) : [
                'error' => 0,
                'data' => $data
            ];
    }
}