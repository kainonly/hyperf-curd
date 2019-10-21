<?php
declare(strict_types=1);

namespace Hyperf\Curd;

use Closure;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\HttpServer\Contract\ResponseInterface;
use Hyperf\Validation\Contract\ValidatorFactoryInterface;
use Psr\Container\ContainerInterface;

abstract class CurdController
{
    /**
     * @Inject
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @Inject
     * @var RequestInterface
     */
    protected $request;

    /**
     * @Inject
     * @var ResponseInterface
     */
    protected $response;

    /**
     * @Inject
     * @var ValidatorFactoryInterface
     */
    protected $validation;

    /**
     * Curd Model Name
     * @var string
     */
    protected $model;

    /**
     * Request Body
     * @var array
     */
    protected $post = [];

    /**
     * Origin Lists Validate
     * @var array
     */
    protected $origin_lists_validate = [];

    /**
     * Origin Lists Default Validate
     * @var array
     */
    protected $origin_lists_default_validate = [
        'where' => 'sometimes|array',
        'where.*' => 'array|size:3'
    ];

    /**
     * Origin Lists Before Response Body
     * @var array
     */
    protected $origin_lists_before_result = [
        'error' => 1,
        'msg' => 'error:before_fail'
    ];

    /**
     * Origin Lists Array Condition
     * @var array
     */
    protected $origin_lists_condition = [];

    /**
     * Origin Lists Query Condition
     * @var Closure|null
     */
    protected $origin_lists_query = null;

    /**
     * Origin Lists OrderBy
     * @var array
     */
    protected $origin_lists_order = ['create_time', 'desc'];

    /**
     * Origin Lists Field
     * @var array
     */
    protected $origin_lists_field = ['*'];

    /**
     * Lists Validate
     * @var array
     */
    protected $lists_validate = [];

    /**
     * Lists Default Validate
     * @var array
     */
    protected $lists_default_validate = [
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
    protected $lists_before_result = [
        'error' => 1,
        'msg' => 'error:before_fail'
    ];

    /**
     * Lists Array Condition
     * @var array
     */
    protected $lists_condition = [];

    /**
     * Lists Query Condition
     * @var Closure|null
     */
    protected $lists_query = null;

    /**
     * Lists OrderBy
     * @var array
     */
    protected $lists_order = ['create_time', 'desc'];

    /**
     * Lists field
     * @var array
     */
    protected $lists_field = ['*'];

    /**
     * Get Validate
     * @var array
     */
    protected $get_validate = [];

    /**
     * Get Default Validate
     * @var array
     */
    protected $get_default_validate = [
        'id' => 'required_without:where|integer',
        'where' => 'required_without:id|array',
        'where.*' => 'array|size:3'
    ];

    /**
     * Get Before Response Body
     * @var array
     */
    protected $get_before_result = [
        'error' => 1,
        'msg' => 'error:before_fail'
    ];

    /**
     * Get Array Condition
     * @var array
     */
    protected $get_condition = [];

    /**
     * Get Field
     * @var array
     */
    protected $get_field = ['*'];

    /**
     * Add Validate
     * @var array
     */
    protected $add_validate = [];

    /**
     * Auto Timestamp
     * @var bool
     */
    protected $add_auto_timestamp = true;

    /**
     * Add Default Validate
     * @var array
     */
    protected $add_default_validate = [
        'id' => 'sometimes|required|integer'
    ];

    /**
     * Add Before Response Body
     * @var array
     */
    protected $add_before_result = [
        'error' => 1,
        'msg' => 'error:before_fail'
    ];

    /**
     * Add After Response Body
     * @var array
     */
    protected $add_after_result = [
        'error' => 1,
        'msg' => 'error:after_fail'
    ];

    /**
     * Add Failed Response Body
     * @var array
     */
    protected $add_fail_result = [
        'error' => 1,
        'msg' => 'error:insert_fail'
    ];

    /**
     * Edit Validate
     * @var array
     */
    protected $edit_validate = [];

    /**
     * Auto Timestamp
     * @var bool
     */
    protected $edit_auto_timestamp = true;

    /**
     * Edit Default Validate
     * @var array
     */
    protected $edit_default_validate = [
        'id' => 'required_without:where|integer',
        'switch' => 'required|bool',
        'where' => 'required_without:id|array',
        'where.*' => 'array|size:3'
    ];

    /**
     * Status Mode
     * @var bool
     */
    protected $edit_switch = false;

    /**
     * Edit Before Response Body
     * @var array
     */
    protected $edit_before_result = [
        'error' => 1,
        'msg' => 'error:before_fail'
    ];

    /**
     * Edit Array Condition
     * @var array
     */
    protected $edit_condition = [];

    /**
     * Edit Failed Response Body
     * @var array
     */
    protected $edit_fail_result = [
        'error' => 1,
        'msg' => 'error:fail'
    ];

    /**
     * Edit After Response Body
     * @var array
     */
    protected $edit_after_result = [
        'error' => 1,
        'msg' => 'error:after_fail'
    ];

    /**
     * Delete Validate
     * @var array
     */
    protected $delete_validate = [];

    /**
     * Delete Default Validate
     * @var array
     */
    protected $delete_default_validate = [
        'id' => 'required_without:where|array',
        'id.*' => 'integer',
        'where' => 'required_without:id|array',
        'where.*' => 'array|size:3'
    ];

    /**
     * Delete Before Response Body
     * @var array
     */
    protected $delete_before_result = [
        'error' => 1,
        'msg' => 'error:before_fail'
    ];

    /**
     * Delete Array Condition
     * @var array
     */
    protected $delete_condition = [];

    /**
     * Delete Before Writing After The Transaction Response Body
     * @var array
     */
    protected $delete_prep_result = [
        'error' => 1,
        'msg' => 'error:prep_fail'
    ];

    /**
     * Delete Failed Reponse Body
     * @var array
     */
    protected $delete_fail_result = [
        'error' => 1,
        'msg' => 'error:fail'
    ];

    /**
     * Delete After Response Body
     * @var array
     */
    protected $delete_after_result = [
        'error' => 1,
        'msg' => 'error:after_fail'
    ];

    public function __construct(
        RequestInterface $request,
        ResponseInterface $response
    )
    {
        $this->post = $request->post();
    }
}
