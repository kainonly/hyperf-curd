<?php
declare(strict_types=1);

namespace Hyperf\Curd\Factory;

use Closure;
use Hyperf\DbConnection\Db;

/**
 * @method void|string afterEvent(int $id)
 */
class AddModel
{
    private string $name;
    private array $body;
    private bool $autoTimestamp = true;
    private Closure $afterEvent;
    private array $resultFailed = [
        'error' => 1,
        'msg' => 'insert failed'
    ];

    public function __construct(string $name, array $body)
    {
        $this->name = $name;
        $this->body = $body;
    }

    /**
     * 自动生成时间戳
     * @param bool $value
     * @return $this
     */
    public function setAutoTimestamp(bool $value): self
    {
        $this->autoTimestamp = $value;
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
            $this->body['create_time'] = $this->body['update_time'] = time();
        }

        $result = null;
        if (empty($this->afterEvent)) {
            $result = Db::table($this->name)->insert($this->body);
        } else {
            $result = Db::transaction(function () {
                $id = null;
                if (!empty($this->body['id'])) {
                    $id = $this->body['id'];
                    $result = Db::table($this->name)->insert($this->body);
                    if (!$result) {
                        return false;
                    }
                } else {
                    $id = Db::table($this->name)->insertGetId($this->body);
                }

                if (empty($id)) {
                    return false;
                }

                $after = $this->afterEvent($id);
                if (!empty($after)) {
                    $this->resultFailed['msg'] = $after;
                    return false;
                }
                return true;
            });
        }

        return !$result ? $this->resultFailed : [
            'error' => 0,
            'msg' => 'ok'
        ];
    }

}