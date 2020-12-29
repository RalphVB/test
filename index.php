<?php

error_reporting(E_ALL);
ini_set('display_errors', '1');

$container = require __DIR__ . "/app/bootstrap.php";

$dispatcher = FastRoute\simpleDispatcher( function( FastRoute\RouteCollector $router ) {
   $router->addRoute("GET", "/", ["Statistic\Controllers\HomeController", "index"]);
});

$httpMethod = $_SERVER["REQUEST_METHOD"];
$uri = rawurldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) );
$route = $dispatcher->dispatch($httpMethod, $uri);

switch($route[0]){
    case FastRoute\Dispatcher::NOT_FOUND:
        echo "404 NOT FOUND";
        break;
    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        echo "405 NOT ALLOWED";
        break;
    case \FastRoute\Dispatcher::FOUND:
        $controller = $route[1];
        $parameters = $route[2];
        $container->call($controller, $parameters);
        break;
}