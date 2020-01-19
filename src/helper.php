<?php
declare(strict_types=1);

use Hyperf\HttpServer\Router\Router;
use Hyperf\Utils\Str;

if (!function_exists('AutoController')) {
    /**
     * @param string $controller
     * @param array $options
     * @throws ReflectionException
     */
    function AutoController(string $controller, array $options = [])
    {
        $reflect = new ReflectionClass($controller);
        $path = lcfirst(Str::before($reflect->getShortName(), 'Controller'));
        $methods = array_filter(
            $reflect->getMethods(ReflectionMethod::IS_PUBLIC),
            fn($v) => !in_array($v->name, config('curd.auto.ignore'))
        );
        foreach ($methods as $method) {
            Router::addRoute(
                ['POST', 'OPTIONS'],
                '/' . $path . '/' . $method->name,
                [$controller, $method->name],
                [
                    'middleware' => []
                ]
            );
        }
    }
}
