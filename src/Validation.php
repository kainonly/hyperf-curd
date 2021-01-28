<?php
declare(strict_types=1);

namespace Hyperf\Curd;

class Validation
{
    public const ORIGINLISTS = [
        'where' => 'sometimes|array',
        'where.*' => 'array|size:3'
    ];

    public const LISTS = [
        'page' => 'required',
        'page.limit' => 'required|integer|between:1,50',
        'page.index' => 'required|integer|min:1',
        'where' => 'sometimes|array',
        'where.*' => 'array|size:3'
    ];

    public const GET = [
        'id' => 'required_without:where|integer',
        'where' => 'required_without:id|array',
        'where.*' => 'array|size:3'
    ];

    public const EDIT = [
        'id' => 'required_without:where|integer',
        'switch' => 'required|bool',
        'where' => 'required_without:id|array',
        'where.*' => 'array|size:3'
    ];

    public const DELETE = [
        'id' => 'required_without:where|array',
        'id.*' => 'integer',
        'where' => 'required_without:id|array',
        'where.*' => 'array|size:3'
    ];
}