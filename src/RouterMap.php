<?php
declare(strict_types=1);

namespace Hyperf\Curd;

use Hyperf\HttpServer\Router\Router;

class RouterMap
{
    /**
     * Set Curd Router
     * @param string $controller
     * @param string $prefix
     * @param array $curd
     * @param array $options
     */
    public static function set(
        string $controller,
        string $prefix,
        array $curd,
        array $options = []
    )
    {
        Router::addGroup($prefix, function () use ($controller, $curd) {
            if (in_array('get', $curd)) {
                Router::post(
                    '/get',
                    $controller . '@get'
                );
            }
            if (in_array('originLists', $curd)) {
                Router::post(
                    '/originLists',
                    $controller . '@originLists'
                );
            }
            if (in_array('lists', $curd)) {
                Router::post(
                    '/lists',
                    $controller . '@lists'
                );
            }
            if (in_array('add', $curd)) {
                Router::post(
                    '/add',
                    $controller . '@add'
                );
            }
            if (in_array('edit', $curd)) {
                Router::post(
                    '/edit',
                    $controller . '@edit'
                );
            }
            if (in_array('delete', $curd)) {
                Router::post(
                    '/delete',
                    $controller . '@delete'
                );
            }
        }, $options);
    }
}