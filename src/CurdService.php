<?php
declare(strict_types=1);

namespace Hyperf\Curd;

use Hyperf\Curd\Factory\AddModel;
use Hyperf\Curd\Factory\DeleteModel;
use Hyperf\Curd\Factory\EditModel;
use Hyperf\Curd\Factory\GetModel;
use Hyperf\Curd\Factory\ListsModel;
use Hyperf\Curd\Factory\OriginListsModel;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\Validation\Contract\ValidatorFactoryInterface;

class CurdService implements CurdInterface
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
     * @param array $default
     * @return array
     * @inheritDoc
     */
    public function originListsValidation(array $validate = [], array $default = []): array
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
     * @param array|null $body
     * @return OriginListsModel
     * @inheritDoc
     */
    public function originListsModel(string $name, ?array $body = null): OriginListsModel
    {
        return new OriginListsModel($name, $body ?? $this->request->post());
    }

    /**
     * @param array $validate
     * @param array $default
     * @return array
     * @inheritDoc
     */
    public function listsValidation(array $validate = [], array $default = []): array
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
     * @param array|null $body
     * @return ListsModel
     * @inheritDoc
     */
    public function listsModel(string $name, ?array $body = null): ListsModel
    {
        return new ListsModel($name, $body ?? $this->request->post());
    }

    /**
     * @param array $validate
     * @param array $default
     * @return array
     * @inheritDoc
     */
    public function getValidation(array $validate = [], array $default = []): array
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
     * @param array|null $body
     * @return GetModel
     * @inheritDoc
     */
    public function getModel(string $name, ?array $body = null): GetModel
    {
        return new GetModel($name, $body ?? $this->request->post());
    }

    /**
     * @param array $validate
     * @param array $default
     * @return array
     * @inheritDoc
     */
    public function addValidation(array $validate = [], array $default = []): array
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
     * @param array|null $body
     * @return AddModel
     * @inheritDoc
     */
    public function addModel(string $name, ?array $body = null): AddModel
    {
        return new AddModel($name, $body ?? $this->request->post());
    }

    /**
     * @param array $validate
     * @param array $default
     * @return array
     * @inheritDoc
     */
    public function editValidation(array $validate = [], array $default = []): array
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
     * @param string $name
     * @param array|null $body
     * @return EditModel
     * @inheritDoc
     */
    public function editModel(string $name, ?array $body = null): EditModel
    {
        return new EditModel($name, $body ?? $this->request->post());
    }

    /**
     * @param array $validate
     * @param array $default
     * @return array
     * @inheritDoc
     */
    public function deleteValidation(array $validate = [], array $default = []): array
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

    /**
     * @param string $name
     * @param array|null $body
     * @return DeleteModel
     * @inheritDoc
     */
    public function deleteModel(string $name, ?array $body = null): DeleteModel
    {
        return new DeleteModel($name, $body ?? $this->request->post());
    }
}