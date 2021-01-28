<?php
declare(strict_types=1);

namespace Hyperf\Curd\Common;

use Hyperf\Curd\CurdInterface;
use Hyperf\Curd\Validation;
use Hyperf\Utils\Context;
use stdClass;

/**
 * Trait DeleteModel
 * @package Hyperf\Curd\Common
 * @property CurdInterface $curd
 * @method bool deleteBeforeHook(stdClass $ctx)
 * @method bool deletePrepHook(stdClass $ctx)
 * @method bool deleteAfterHook(stdClass $ctx)
 */
trait DeleteModel
{
    public function delete(): array
    {
        $body = $this->curd->should(Validation::DELETE, static::$deleteValidate);
        $ctx = new stdClass();
        $ctx->body = &$body;
        if (method_exists($this, 'deleteBeforeHook') && !$this->deleteBeforeHook($ctx)) {
            return Context::get('error', [
                'error' => 1,
                'msg' => 'An exception occurred in the before hook'
            ]);
        }
        $model = $this->curd->model(static::$model, $body);
        if (!empty(static::$deleteCondition)) {
            $model = $model->where(static::$deleteCondition);
        }
        if (method_exists($this, 'deletePrepHook')) {
            $model = $model->prepHook(fn() => $this->deletePrepHook($ctx));
        }
        if (method_exists($this, 'deleteAfterHook')) {
            $model = $model->afterHook(fn() => $this->deleteAfterHook($ctx));
        }
        return $model->delete();
    }
}