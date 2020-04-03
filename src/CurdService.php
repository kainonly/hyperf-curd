<?php
declare(strict_types=1);

namespace Hyperf\Curd;

use Hyperf\Curd\Factory\AddModel;
use Hyperf\Curd\Factory\GetModel;
use Hyperf\Curd\Factory\ListsModel;
use Hyperf\Curd\Factory\OriginListsModel;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\Validation\Contract\ValidatorFactoryInterface;

class CurdService
{
    private RequestInterface $request;
    private ValidatorFactoryInterface $validation;

    public function __construct(
        RequestInterface $request,
        ValidatorFactoryInterface $validation
    )
    {
        $this->request = $request;
        $this->validation = $validation;
    }

    /**
     * @param array $validate
     * @param array|null $default
     * @return array
     */
    public function originListsValidation(array $validate, ?array $default): array
    {
        $body = $this->request->post();
        $validator = $this->validation->make($body, array_merge(
            $validate,
            $default ?? [
                'where' => 'sometimes|array',
                'where.*' => 'array|size:3'
            ]
        ));

        return $validator->fails() ? [
            'error' => 1,
            'msg' => $validator->errors()
        ] : [
            'error' => 0
        ];
    }

    /**
     * @param string $name
     * @return OriginListsModel
     */
    public function originListsModel(string $name): OriginListsModel
    {
        $body = $this->request->post();
        return new OriginListsModel($name, $body);
    }

    /**
     * @param array $validate
     * @param array|null $default
     * @return array
     */
    public function listsValidation(array $validate, ?array $default): array
    {
        $body = $this->request->post();
        $validator = $this->validation->make($body, array_merge(
            $validate,
            $default ?? [
                'page' => 'required',
                'page.limit' => 'required|integer|between:1,50',
                'page.index' => 'required|integer|min:1',
                'where' => 'sometimes|array',
                'where.*' => 'array|size:3'
            ]
        ));

        return $validator->fails() ? [
            'error' => 1,
            'msg' => $validator->errors()
        ] : [
            'error' => 0
        ];
    }

    /**
     * @param string $name
     * @return ListsModel
     */
    public function listsModel(string $name): ListsModel
    {
        $body = $this->request->post();
        return new ListsModel($name, $body);
    }

    /**
     * @param array $validate
     * @param array|null $default
     * @return array
     */
    public function getValidation(array $validate, ?array $default): array
    {
        $body = $this->request->post();
        $validator = $this->validation->make($body, array_merge(
            $validate,
            $default ?? [
                'id' => 'required_without:where|integer',
                'where' => 'required_without:id|array',
                'where.*' => 'array|size:3'
            ]
        ));

        return $validator->fails() ? [
            'error' => 1,
            'msg' => $validator->errors()
        ] : [
            'error' => 0
        ];
    }

    /**
     * @param string $name
     * @return GetModel
     */
    public function getModel(string $name): GetModel
    {
        $body = $this->request->post();
        return new GetModel($name, $body);
    }

    /**
     * @param array $validate
     * @param array|null $default
     * @return array
     */
    public function addValidation(array $validate, ?array $default): array
    {
        $body = $this->request->post();
        $validator = $this->validation->make($body, array_merge(
            $validate,
            $default ?? []
        ));

        return $validator->fails() ? [
            'error' => 1,
            'msg' => $validator->errors()
        ] : [
            'error' => 0
        ];
    }

    /**
     * @param string $name
     * @return AddModel
     */
    public function addModel(string $name): AddModel
    {
        $body = $this->request->post();
        return new AddModel($name, $body);
    }

    /**
     * @param array $validate
     * @param array|null $default
     * @return array
     */
    public function editValidation(array $validate, ?array $default): array
    {
        $body = $this->request->post();
        $validator = $this->validation->make($body, $default ?? [
                'id' => 'required_without:where|integer',
                'switch' => 'required|bool',
                'where' => 'required_without:id|array',
                'where.*' => 'array|size:3'
            ]
        );

        if ($validator->fails()) {
            return [
                'error' => 1,
                'msg' => $validator->errors()
            ];
        }

        if (!$body['switch']) {
            $validator = $this->validation->make($body, $validate);
            if ($validator->fails()) {
                return [
                    'error' => 1,
                    'msg' => $validator->errors()
                ];
            }
        }

        return [
            'error' => 0
        ];
    }

    /**
     * @param array $validate
     * @param array|null $default
     * @return array
     */
    public function deleteValidation(array $validate, ?array $default): array
    {
        $body = $this->request->post();
        $validator = $this->validation->make($body, array_merge(
            $validate,
            $default ?? [
                'id' => 'required_without:where|array',
                'id.*' => 'integer',
                'where' => 'required_without:id|array',
                'where.*' => 'array|size:3'
            ]
        ));

        return $validator->fails() ? [
            'error' => 1,
            'msg' => $validator->errors()
        ] : [
            'error' => 0
        ];
    }
}