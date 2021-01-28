<?php
declare(strict_types=1);

namespace Hyperf\Curd\Common;

use Hyperf\Curd\CurdInterface;
use Hyperf\Curd\Validation;
use Hyperf\Utils\Context;
use stdClass;

/**
 * Trait EditModel
 * @package Hyperf\Curd\Common
 * @property CurdInterface $curd
 */
trait EditModel
{
    public function edit(): array
    {
        $body = $this->curd->should(Validation::EDIT, static::$editValidate);
        $ctx = new stdClass();
        $ctx->body = &$body;
        $ctx->switch = $body['switch'];
        if (method_exists($this, 'editBeforeHook') && !$this->editBeforeHook($ctx)) {
            return Context::get('error', [
                'error' => 1,
                'msg' => 'An exception occurred in the before hook'
            ]);
        }
        $model = $this->curd->model(static::$model, $body)->autoTimestamp(static::$autoTimestamp);
        if (!empty(static::$editCondition)) {
            $model = $model->where(static::$editCondition);
        }
        if (method_exists($this, 'editAfterHook')) {
            $model = $model->afterHook($this->editAfterHook($ctx));
        }
        return $model->edit();
    }
}