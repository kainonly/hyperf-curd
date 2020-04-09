<?php
declare(strict_types=1);

namespace Hyperf\Curd\Factory;

use Closure;
use Hyperf\Curd\Common\DeleteAfterParams;
use Hyperf\Curd\Common\DeletePrepParams;
use Hyperf\DbConnection\Db;
use Hyperf\Utils\Context;

class DeleteModel extends BaseModel
{
    /**
     * 条件数组
     * @var array
     */
    private array $condition = [];
    /**
     * 事务准备闭包
     * @var Closure|null
     */
    private ?Closure $prep = null;
    /**
     * 后置闭包
     * @var Closure|null
     */
    private ?Closure $after = null;
    /**
     * 返回错误
     * @var array
     */
    private array $error = [
        'error' => 1,
        'msg' => 'delete failed'
    ];

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
     * 执行
     * @return array
     */
    public function result(): array
    {
        return !Db::transaction(function () {
            if (!empty($this->prep)) {
                $param = new DeletePrepParams();
                $param->setId($this->body['id']);
                $param->setBody($this->body);
                $func = $this->prep;
                if (!$func($param)) {
                    $this->error = Context::get('error', [
                        'error' => 1,
                        'msg' => 'prep hook failed'
                    ]);
                    return false;
                }
            }

            $convert = $this->convertConditions($this->condition);

            if (!empty($this->body['id'])) {
                $query = Db::table($this->name)
                    ->whereIn('id', $this->body['id'])
                    ->where($convert->simple);
            } else {
                $query = Db::table($this->name)
                    ->where($this->body['where'])
                    ->where($convert->simple);
            }

            if (!empty($convert->additional)) {
                $query = $this->autoAdditionalClauses(
                    $query,
                    $convert->additional
                );
            }

            $result = $query->delete();

            if (!$result) {
                return false;
            }

            if (!empty($this->after)) {
                $param = new DeleteAfterParams();
                $param->setId($this->body['id']);
                $func = $this->after;
                if (!$func($param)) {
                    $this->error = Context::get('error', [
                        'error' => 1,
                        'msg' => 'after hook failed'
                    ]);
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