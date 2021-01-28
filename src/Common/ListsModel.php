<?php
declare(strict_types=1);

namespace Hyperf\Curd\Common;

use Hyperf\Curd\CurdInterface;
use Hyperf\Curd\Validation;

/**
 * Trait ListsModel
 * @package Hyperf\Curd\Common
 * @property CurdInterface $curd
 */
trait ListsModel
{
    public function lists(): array
    {
        $body = $this->curd->should(Validation::LISTS, static::$listsValidate);
        $model = $this->curd->model(static::$model, $body);
        if (!empty(static::$listsCondition)) {
            $model = $model->where(static::$listsCondition);
        }
        if (method_exists($this, 'listsConditionQuery')) {
            $model = $model->query($this->listsConditionQuery($body));
        }
        if (!empty(static::$listsOrders)) {
            $model = $model->orderBy(static::$listsOrders);
        }
        if (!empty(static::$listsField)) {
            $model = $model->select(static::$listsField);
        }
        $result = $model->lists();
        if (method_exists($this, 'listsCustomReturn')) {
            return $this->listsCustomReturn($result);
        }
        return $result;
    }
}