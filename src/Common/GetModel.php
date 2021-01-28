<?php
declare(strict_types=1);

namespace Hyperf\Curd\Common;

use Closure;
use Hyperf\Curd\CurdInterface;
use Hyperf\Curd\Validation;

/**
 * Trait GetModel
 * @package Hyperf\Curd\Common
 * @property CurdInterface $curd
 * @method Closure getConditionQuery(array $body)
 * @method array getCustomReturn(array $body, array $result)
 */
trait GetModel
{
    public function get(): array
    {
        $body = $this->curd->should(Validation::GET, static::$getValidate);
        $model = $this->curd->model(static::$model, $body);
        if (!empty(static::$getCondition)) {
            $model = $model->where(static::$getCondition);
        }
        if (method_exists($this, 'getConditionQuery')) {
            $model = $model->query($this->getConditionQuery($body));
        }
        if (!empty(static::$getOrders)) {
            $model = $model->orderBy(static::$getOrders);
        }
        if (!empty(static::$getField)) {
            $model = $model->select(static::$getField);
        }
        $result = $model->get();
        if (method_exists($this, 'getCustomReturn')) {
            return $this->getCustomReturn($body, $result);
        }
        return $result;
    }
}