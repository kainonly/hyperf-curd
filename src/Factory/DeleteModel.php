<?php
declare(strict_types=1);

namespace Hyperf\Curd\Factory;

use Closure;
use Hyperf\DbConnection\Db;

/**
 * @method void|string prepEvent(array &$body)
 * @method void|string afterEvent(int $id)
 */
class DeleteModel
{
    private string $name;
    private array $body;
    private array $condition = [];
    private ?Closure $prepEvent = null;
    private ?Closure $afterEvent = null;
    private array $resultFailed = [
        'error' => 1,
        'msg' => 'delete failed'
    ];

    public function __construct(string $name, array $body)
    {
        $this->name = $name;
        $this->body = $body;
    }

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
    public function onPrepEvent(Closure $value): self
    {
        $this->prepEvent = $value;
        return $this;
    }

    /**
     * 监听后置处理
     * @param Closure $value
     * @return $this
     */
    public function onAfterEvent(Closure $value): self
    {
        $this->afterEvent = $value;
        return $this;
    }

    /**
     * 执行
     * @return array
     */
    public function result(): array
    {
        return !Db::transaction(function () {
            $prep = $this->prepEvent($this->body);
            if (!empty($prep)) {
                $this->resultFailed['msg'] = $prep;
                return false;
            }

            $condition = $this->condition;
            if (!empty($this->body['id'])) {
                $result = Db::table($this->name)
                    ->whereIn('id', $this->body['id'])
                    ->where($condition)
                    ->delete();
            } else {
                $result = Db::table($this->name)
                    ->where($this->body['where'])
                    ->where($condition)
                    ->delete();
            }

            if (!$result) {
                return false;
            }

            $after = $this->afterEvent($this->body['id']);
            if (!empty($after)) {
                $this->resultFailed['msg'] = $after;
                return false;
            }

            return true;
        }) ? $this->resultFailed : [
            'error' => 0,
            'msg' => 'ok'
        ];
    }
}