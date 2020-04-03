<?php
declare(strict_types=1);

namespace Hyperf\Curd;

use Hyperf\Curd\Factory\AddModel;
use Hyperf\Curd\Factory\DeleteModel;
use Hyperf\Curd\Factory\EditModel;
use Hyperf\Curd\Factory\GetModel;
use Hyperf\Curd\Factory\ListsModel;
use Hyperf\Curd\Factory\OriginListsModel;

interface CurdInterface
{
    /**
     * 列表请求验证
     * @param array $validate
     * @param array|null $default
     * @return array
     */
    public function originListsValidation(array $validate, ?array $default): array;

    /**
     * 列表请求模型
     * @param string $name
     * @return OriginListsModel
     */
    public function originListsModel(string $name): OriginListsModel;

    /**
     * 分页请求验证
     * @param array $validate
     * @param array|null $default
     * @return array
     */
    public function listsValidation(array $validate, ?array $default): array;

    /**
     * 分页请求模型
     * @param string $name
     * @return ListsModel
     */
    public function listsModel(string $name): ListsModel;

    /**
     * 获取数据请求验证
     * @param array $validate
     * @param array|null $default
     * @return array
     */
    public function getValidation(array $validate, ?array $default): array;

    /**
     * 获取数据请求模型
     * @param string $name
     * @return GetModel
     */
    public function getModel(string $name): GetModel;

    /**
     * 新增数据请求验证
     * @param array $validate
     * @param array|null $default
     * @return array
     */
    public function addValidation(array $validate, ?array $default): array;

    /**
     * 新增数据请求模型
     * @param string $name
     * @return AddModel
     */
    public function addModel(string $name): AddModel;

    /**
     * 编辑数据请求验证
     * @param array $validate
     * @param array|null $default
     * @return array
     */
    public function editValidation(array $validate, ?array $default): array;

    /**
     * 编辑数据请求模型
     * @param string $name
     * @return EditModel
     */
    public function editModel(string $name): EditModel;

    /**
     * 删除数据请求验证
     * @param array $validate
     * @param array|null $default
     * @return array
     */
    public function deleteValidation(array $validate, ?array $default): array;

    /**
     * 删除数据请求模型
     * @param string $name
     * @return DeleteModel
     */
    public function deleteModel(string $name): DeleteModel;
}