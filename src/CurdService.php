<?php
declare(strict_types=1);

namespace Hyperf\Curd;

use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\Validation\Contract\ValidatorFactoryInterface;
use Hyperf\Validation\ValidationException;

class CurdService implements CurdInterface
{
    private RequestInterface $request;
    private ValidatorFactoryInterface $validation;

    /**
     * CurdService constructor.
     * @param RequestInterface $request
     * @param ValidatorFactoryInterface $validation
     */
    public function __construct(
        RequestInterface $request,
        ValidatorFactoryInterface $validation
    )
    {
        $this->request = $request;
        $this->validation = $validation;
    }

    public function should(array $rule = [], ...$extend): array
    {
        $body = $this->request->post();
        if (!empty($rule)) {
            $validate = $this->validation->make($body, array_merge($rule, ...$extend));
            if ($validate->fails()) {
                throw new ValidationException($validate);
            }
        }
        return $body;
    }

    public function model(string $name, array $body): CurdFactory
    {
        $curd = new CurdFactory();
        $curd->name = $name;
        $curd->body = $body;
        return $curd;
    }
}