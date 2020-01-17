<?php
declare(strict_types=1);

use \Hyperf\HttpServer\Router\Router;
use Hyperf\Utils\Str;

if (!function_exists('addCurdRoutes')) {
    /**
     * Load CURD route
     * @param string $controller
     * @param array $options
     * @throws ReflectionException
     */
    function addCurdRoutes(string $controller, array $options = []): void
    {
        $reflect = new ReflectionClass($controller);
        $path = lcfirst(Str::before($reflect->getShortName(), 'Controller'));
        if ($reflect->hasMethod('get')) {
            Router::post('/' . $path . '/get', [$controller, 'get'], $options);
        }
        if ($reflect->hasMethod('originLists')) {
            Router::post('/' . $path . '/originLists', [$controller, 'originLists'], $options);
        }
        if ($reflect->hasMethod('lists')) {
            Router::post('/' . $path . '/lists', [$controller, 'lists'], $options);
        }
        if ($reflect->hasMethod('add')) {
            Router::post('/' . $path . '/add', [$controller, 'add'], $options);
        }
        if ($reflect->hasMethod('edit')) {
            Router::post('/' . $path . '/edit', [$controller, 'edit'], $options);
        }
        if ($reflect->hasMethod('delete')) {
            Router::post('/' . $path . '/delete', [$controller, 'delete'], $options);
        }
    }
}
