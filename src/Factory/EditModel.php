<?php
declare(strict_types=1);

namespace Hyperf\Curd\Factory;

use Closure;
use Hyperf\Curd\Common\EditAfterParams;
use Hyperf\DbConnection\Db;
use Hyperf\Utils\Context;

class EditModel extends BaseModel
{
    /**
     * 条件数组
     * @var array
     */
    private array $condition = [];
    /**
     * 自动生成时间戳
     * @var bool
     */
    private bool $autoTimestamp = true;
    /**
     * 是否为状态修改
     * @var bool|mixed
     */
    private bool $switch = false;
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
        'msg' => 'edit failed'
    ];

    /**
     * EditModel constructor.
     * @param string $name
     * @param array $body
     */
    public function __construct(string $name, array $body)
    {
        parent::__construct($name, $body);
        $this->switch = $body['switch'];
        unset($this->body['switch']);
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
        if ($this->autoTimestamp) {
            $this->body['update_time'] = time();
        }

        return !Db::transaction(function () {
            $condition = [
                ...$this->condition,
                ...!empty($this->body['id']) ? [['id', '=', $this->body['id']]] : $this->body['where']
            ];

            unset($this->body['where']);

            $convert = $this->convertConditions($condition);

            $query = Db::table($this->name)
                ->where($convert->simple);

            if (!empty($convert->additional)) {
                $query = $this->autoAdditionalClauses(
                    $query,
                    $convert->additional
                );
            }

            $query->update($this->body);

            if (!empty($this->after)) {
                $param = new EditAfterParams();
                $param->setId((int)$this->body['id']);
                $param->setSwitch($this->switch);
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