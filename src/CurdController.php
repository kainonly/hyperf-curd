<?php
declare(strict_types=1);

namespace Hyperf\Curd;

abstract class CurdController
{
    /**
     * 模型名称
     * @var string
     */
    protected static string $model;

    /**
     * 列表验证
     * @var array
     */
    protected static array $originListsValidate = [];

    /**
     * 列表条件
     * @var array
     */
    protected static array $originListsCondition = [];

    /**
     * 列表排序
     * @var array
     */
    protected static array $originListsOrders = ['create_time' => 'desc'];

    /**
     * 列表字段
     * @var array
     */
    protected static array $originListsField = [];

    /**
     * 分页验证
     * @var array
     */
    protected static array $listsValidate = [];

    /**
     * 分页条件
     * @var array
     */
    protected static array $listsCondition = [];

    /**
     * 分页排序
     * @var array
     */
    protected static array $listsOrders = ['create_time' => 'desc'];

    /**
     * 分页字段
     * @var array
     */
    protected static array $listsField = [];

    /**
     * 数据验证
     * @var array
     */
    protected static array $getValidate = [];

    /**
     * 数据条件
     * @var array
     */
    protected static array $getCondition = [];

    /**
     * 数据排序
     * @var array
     */
    protected static array $getOrders = [];

    /**
     * 数据字段
     * @var array
     */
    protected static array $getField = [];

    /**
     * 自动更新时间戳
     * @var bool
     */
    protected static bool $autoTimestamp = true;

    /**
     * 新增模型名称
     * @var string
     */
    protected static string $addModel;

    /**
     * 新增验证
     * @var array
     */
    protected static array $addValidate = [];

    /**
     * 编辑模型名称
     * @var string
     */
    protected static string $editModel;

    /**
     * 编辑验证
     * @var array
     */
    protected static array $editValidate = [];

    /**
     * 编辑条件
     * @var array
     */
    protected static array $editCondition = [];

    /**
     * 删除模型名称
     * @var string
     */
    protected static string $deleteModel;

    /**
     * 删除验证
     * @var array
     */
    protected static array $deleteValidate = [];

    /**
     * 删除条件
     * @var array
     */
    protected static array $deleteCondition = [];
}