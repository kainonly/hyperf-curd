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
        $path = Str::snake(Str::before($reflect->getShortName(), 'Controller'), '_');
        $methods = array_filter(
            $reflect->getMethods(ReflectionMethod::IS_PUBLIC),
            fn($v) => !in_array($v->name, config('curd.auto.ignore'), true)
        );
        $middlewares = $options['middleware'] ?? [];
        foreach ($methods as $method) {
            $middleware = [];
            foreach ($middlewares as $key => $value) {
                if (is_string($value)) {
                    $middleware[] = $value;
                }
                if (is_array($value) && in_array($method->name, $value, true)) {
                    $middleware[] = $key;
                }
            }
            Router::addRoute(
                ['POST', 'OPTIONS'],
                '/' . $path . '/' . $method->name,
                [$controller, $method->name],
                [
                    'middleware' => $middleware
                ]
            );
        }
    }
}
