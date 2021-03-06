<?php
declare(strict_types=1);

namespace Hyperf\Curd\Common;

use Closure;
use Hyperf\Curd\CurdInterface;
use Hyperf\Curd\Validation;

/**
 * Trait OriginListsModel
 * @package Hyperf\Curd\Common
 * @property CurdInterface $curd
 * @method Closure originListsConditionQuery(array $body)
 * @method array originListsCustomReturn(array $body, array $result)
 */
trait OriginListsModel
{
    public function originLists(): array
    {
        $body = $this->curd->should(Validation::ORIGINLISTS, static::$originListsValidate);
        $model = $this->curd->model(static::$model, $body);
        if (!empty(static::$originListsCondition)) {
            $model = $model->where(static::$originListsCondition);
        }
        if (method_exists($this, 'originListsConditionQuery')) {
            $model = $model->query($this->originListsConditionQuery($body));
        }
        if (!empty(static::$originListsOrders)) {
            $model = $model->orderBy(static::$originListsOrders);
        }
        if (!empty(static::$originListsField)) {
            $model = $model->select(static::$originListsField);
        }
        $result = $model->originLists();
        if (method_exists($this, 'originListsCustomReturn')) {
            return $this->originListsCustomReturn($body, $result);
        }
        return $result;
    }
}