<?php

error_reporting(E_ALL);
ini_set('display_errors', '1');

require "vendor/autoload.php";

$dispatcher = FastRoute\simpleDispatcher( function( \FastRoute\RouteCollector $route ) {
   $route->addRoute("GET", "/", "index"); 
});

$httpMethod = $_SERVER["REQUEST_METHOD"];

$uri = rawurldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) );

$routeInfo = $dispatcher->dispatch($httpMethod, $uri);

switch($routeInfo[0]){
    case FastRoute\Dispatcher::NOT_FOUND:
        echo "Rute no encontrada.";
        break;
    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        echo "Ruta no permitida.";
        break;
    case \FastRoute\Dispatcher::FOUND:
        echo "Ruta encontrada.";
        break;
}