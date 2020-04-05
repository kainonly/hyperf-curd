<?php
declare(strict_types=1);

namespace Hyperf\Curd;

use Hyperf\Contract\ValidatorInterface;
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
     * @return ValidatorInterface
     */
    public function originListsValidation(array $validate = [], ?array $default = null): ValidatorInterface;

    /**
     * 列表请求模型
     * @param string $name
     * @param array $body
     * @return OriginListsModel
     */
    public function originListsModel(string $name, array $body = []): OriginListsModel;

    /**
     * 分页请求验证
     * @param array $validate
     * @param array|null $default
     * @return ValidatorInterface
     */
    public function listsValidation(array $validate = [], ?array $default = null): ValidatorInterface;

    /**
     * 分页请求模型
     * @param string $name
     * @param array $body
     * @return ListsModel
     */
    public function listsModel(string $name, array $body = []): ListsModel;

    /**
     * 获取数据请求验证
     * @param array $validate
     * @param array|null $default
     * @return ValidatorInterface
     */
    public function getValidation(array $validate = [], ?array $default = null): ValidatorInterface;

    /**
     * 获取数据请求模型
     * @param string $name
     * @param array $body
     * @return GetModel
     */
    public function getModel(string $name, array $body = []): GetModel;

    /**
     * 新增数据请求验证
     * @param array $validate
     * @param array|null $default
     * @return ValidatorInterface
     */
    public function addValidation(array $validate = [], ?array $default = null): ValidatorInterface;

    /**
     * 新增数据请求模型
     * @param string $name
     * @param array $body
     * @return AddModel
     */
    public function addModel(string $name, array $body = []): AddModel;

    /**
     * 编辑数据请求验证
     * @param array $validate
     * @param array|null $default
     * @return ValidatorInterface
     */
    public function editValidation(array $validate = [], ?array $default = null): ValidatorInterface;

    /**
     * 编辑数据请求模型
     * @param string $name
     * @param array $body
     * @return EditModel
     */
    public function editModel(string $name, array $body = []): EditModel;

    /**
     * 删除数据请求验证
     * @param array $validate
     * @param array|null $default
     * @return ValidatorInterface
     */
    public function deleteValidation(array $validate = [], ?array $default = null): ValidatorInterface;

    /**
     * 删除数据请求模型
     * @param string $name
     * @param array $body
     * @return DeleteModel
     */
    public function deleteModel(string $name, array $body = []): DeleteModel;
}