<?php
declare(strict_types=1);

namespace Hyperf\Curd\Common;

use Hyperf\DbConnection\Db;
use Hyperf\HttpServer\Annotation\PostMapping;
use Hyperf\Validation\Contract\ValidatorFactoryInterface;

/**
 * Trait AddModel
 * @package Hyperf\Curd\Common
 * @property ValidatorFactoryInterface $validation
 * @property string $model
 * @property array $post
 * @property array $add_validate
 * @property bool $add_auto_timestamp
 * @property array $add_default_validate
 * @property array $add_before_result
 * @property array $add_after_result
 * @property array $add_fail_result
 */
trait AddModel
{
    /**
     * @return array
     * @PostMapping(path="add")
     */
    public function add()
    {
        $validator = $this->validation->make($this->post, array_merge(
            $this->add_validate,
            $this->add_default_validate
        ));

        if ($validator->fails()) {
            return [
                'error' => 1,
                'msg' => $validator->errors()
            ];
        }

        if ($this->add_auto_timestamp) {
            $this->post['create_time'] = $this->post['update_time'] = time();
        }

        if (method_exists($this, '__addBeforeHooks') &&
            !$this->__addBeforeHooks()) {
            return $this->add_before_result;
        }

        return !DB::transaction(function () {
            if (!method_exists($this, '__addAfterHooks')) {
                return DB::table($this->model)
                    ->insert($this->post);
            }

            $id = null;
            if (!empty($this->post['id'])) {
                $id = $this->post['id'];
                $result = DB::table($this->model)
                    ->insert($this->post);
                if (!$result) {
                    return false;
                }
            } else {
                $id = DB::table($this->model)->insertGetId($this->post);
            }

            if (empty($id) || !$this->__addAfterHooks($id)) {
                $this->add_fail_result = $this->add_after_result;
                Db::rollback();
                return false;
            }

            return true;
        }) ? $this->add_fail_result : [
            'error' => 0,
            'msg' => 'ok'
        ];
    }
}
