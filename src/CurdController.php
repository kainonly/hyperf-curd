<?php
declare(strict_types=1);

namespace Hyperf\Curd;

use Closure;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\HttpServer\Contract\ResponseInterface;
use Hyperf\Validation\Contract\ValidatorFactoryInterface;
use Psr\Container\ContainerInterface;

abstract class CurdController
{
    protected ContainerInterface $container;
    protected RequestInterface $request;
    protected ResponseInterface $response;
    protected ValidatorFactoryInterface $validation;

    /**
     * Curd Model Name
     * @var string
     */
    protected string $model;

    /**
     * Request body
     * @var array
     */
    protected array $post = [];

    /**
     * Origin Lists Validate
     * @var array
     */
    protected array $origin_lists_validate = [];

    /**
     * Origin Lists Default Validate
     * @var array
     */
    protected array $origin_lists_default_validate = [
        'where' => 'sometimes|array',
        'where.*' => 'array|size:3'
    ];

    /**
     * Origin Lists Before Response Body
     * @var array
     */
    protected array $origin_lists_before_result = [
        'error' => 1,
        'msg' => 'error:before_fail'
    ];

    /**
     * Origin Lists Array Condition
     * @var array
     */
    protected array $origin_lists_condition = [];

    /**
     * Origin Lists Query Condition
     * @var Closure|null
     */
    protected ?Closure $origin_lists_query = null;

    /**
     * Origin Lists OrderBy
     * @var array
     */
    protected array $origin_lists_order = ['create_time', 'desc'];

    /**
     * Origin Lists Field
     * @var array
     */
    protected array $origin_lists_field = ['*'];

    /**
     * Lists Validate
     * @var array
     */
    protected array $lists_validate = [];

    /**
     * Lists Default Validate
     * @var array
     */
    protected array $lists_default_validate = [
        'page' => 'required',
        'page.limit' => 'required|integer|between:1,50',
        'page.index' => 'required|integer|min:1',
        'where' => 'sometimes|array',
        'where.*' => 'array|size:3'
    ];

    /**
     * Lists Before Response Body
     * @var array
     */
    protected array $lists_before_result = [
        'error' => 1,
        'msg' => 'error:before_fail'
    ];

    /**
     * Lists Array Condition
     * @var array
     */
    protected array $lists_condition = [];

    /**
     * Lists Query Condition
     * @var Closure|null
     */
    protected ?Closure $lists_query = null;

    /**
     * Lists OrderBy
     * @var array
     */
    protected array $lists_order = ['create_time', 'desc'];

    /**
     * Lists field
     * @var array
     */
    protected array $lists_field = ['*'];

    /**
     * Get Validate
     * @var array
     */
    protected array $get_validate = [];

    /**
     * Get Default Validate
     * @var array
     */
    protected array $get_default_validate = [
        'id' => 'required_without:where|integer',
        'where' => 'required_without:id|array',
        'where.*' => 'array|size:3'
    ];

    /**
     * Get Before Response Body
     * @var array
     */
    protected array $get_before_result = [
        'error' => 1,
        'msg' => 'error:before_fail'
    ];

    /**
     * Get Array Condition
     * @var array
     */
    protected array $get_condition = [];

    /**
     * Get Field
     * @var array
     */
    protected array $get_field = ['*'];

    /**
     * Add Model
     * @var string
     */
    protected string $add_model;

    /**
     * Add Validate
     * @var array
     */
    protected array $add_validate = [];

    /**
     * Auto Timestamp
     * @var bool
     */
    protected bool $add_auto_timestamp = true;

    /**
     * Add Default Validate
     * @var array
     */
    protected array $add_default_validate = [
        'id' => 'sometimes|required|integer'
    ];

    /**
     * Add Before Response Body
     * @var array
     */
    protected array $add_before_result = [
        'error' => 1,
        'msg' => 'error:before_fail'
    ];

    /**
     * Add After Response Body
     * @var array
     */
    protected array $add_after_result = [
        'error' => 1,
        'msg' => 'error:after_fail'
    ];

    /**
     * Add Failed Response Body
     * @var array
     */
    protected array $add_fail_result = [
        'error' => 1,
        'msg' => 'error:insert_fail'
    ];

    /**
     * Edit Model
     * @var string
     */
    protected string $edit_model;

    /**
     * Edit Validate
     * @var array
     */
    protected array $edit_validate = [];

    /**
     * Auto Timestamp
     * @var bool
     */
    protected bool $edit_auto_timestamp = true;

    /**
     * Edit Default Validate
     * @var array
     */
    protected array $edit_default_validate = [
        'id' => 'required_without:where|integer',
        'switch' => 'required|bool',
        'where' => 'required_without:id|array',
        'where.*' => 'array|size:3'
    ];

    /**
     * Status Mode
     * @var bool
     */
    protected bool $edit_switch = false;

    /**
     * Edit Before Response Body
     * @var array
     */
    protected array $edit_before_result = [
        'error' => 1,
        'msg' => 'error:before_fail'
    ];

    /**
     * Edit Array Condition
     * @var array
     */
    protected array $edit_condition = [];

    /**
     * Edit Failed Response Body
     * @var array
     */
    protected array $edit_fail_result = [
        'error' => 1,
        'msg' => 'error:fail'
    ];

    /**
     * Edit After Response Body
     * @var array
     */
    protected array $edit_after_result = [
        'error' => 1,
        'msg' => 'error:after_fail'
    ];

    /**
     * Delete Model
     * @var string
     */
    protected string $delete_model;

    /**
     * Delete Validate
     * @var array
     */
    protected array $delete_validate = [];

    /**
     * Delete Default Validate
     * @var array
     */
    protected array $delete_default_validate = [
        'id' => 'required_without:where|array',
        'id.*' => 'integer',
        'where' => 'required_without:id|array',
        'where.*' => 'array|size:3'
    ];

    /**
     * Delete Before Response Body
     * @var array
     */
    protected array $delete_before_result = [
        'error' => 1,
        'msg' => 'error:before_fail'
    ];

    /**
     * Delete Array Condition
     * @var array
     */
    protected array $delete_condition = [];

    /**
     * Delete Before Writing After The Transaction Response Body
     * @var array
     */
    protected array $delete_prep_result = [
        'error' => 1,
        'msg' => 'error:prep_fail'
    ];

    /**
     * Delete Failed Reponse Body
     * @var array
     */
    protected array $delete_fail_result = [
        'error' => 1,
        'msg' => 'error:fail'
    ];

    /**
     * Delete After Response Body
     * @var array
     */
    protected array $delete_after_result = [
        'error' => 1,
        'msg' => 'error:after_fail'
    ];

    public function __construct(
        ContainerInterface $container,
        RequestInterface $request,
        ResponseInterface $response,
        ValidatorFactoryInterface $validation
    )
    {
        $this->container = $container;
        $this->request = $request;
        $this->response = $response;
        $this->validation = $validation;
        $this->post = $request->post();
    }
}
