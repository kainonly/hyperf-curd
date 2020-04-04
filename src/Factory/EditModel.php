<?php
declare(strict_types=1);

namespace Hyperf\Curd\Factory;

use Closure;
use Hyperf\DbConnection\Db;

/**
 * @method void|string afterEvent(int $id, bool $switch)
 */
class EditModel
{
    private string $name;
    private array $body;
    private array $condition = [];
    private bool $autoTimestamp = true;
    private bool $switch = false;
    private ?Closure $afterEvent = null;
    private array $resultFailed = [
        'error' => 1,
        'msg' => 'edit failed'
    ];

    public function __construct(string $name, array $body)
    {
        $this->name = $name;
        $this->body = $body;
        $this->switch = $body['switch'];
        unset($this->body['switch']);
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
        if ($this->autoTimestamp) {
            $this->body['update_time'] = time();
        }

        return !Db::transaction(function () {
            $condition = [
                ...$this->condition,
                ...!empty($this->body['id']) ? [['id', '=', $this->body['id']]] : $this->body['where']
            ];
            unset($this->body['where']);

            $query = Db::table($this->name)
                ->where($condition)
                ->update($this->body);

            if (!$query) {
                return false;
            }

            $after = $this->afterEvent($this->body['id'], $this->switch);
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