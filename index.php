<?php
declare(strict_types=1);

require_once __DIR__ . '/vendor/autoload.php';

use DI\ContainerBuilder;
use Catbot\Main;
use FastRoute\RouteCollector;
use Middlewares\FastRoute;
use Middlewares\RequestHandler;
use Relay\Relay;
use Zend\Diactoros\ServerRequestFactory;
use Zend\Diactoros\Response;
use Zend\Diactoros\Response\SapiEmitter;
use function DI\create;
use function FastRoute\simpleDispatcher;
use function DI\get;

$BASE_URL = '';

$containerBuilder = new ContainerBuilder();
$containerBuilder->useAutowiring(false);
$containerBuilder->useAnnotations(false);
$containerBuilder->addDefinitions([
    Main::class => create(Main::class)->constructor(),
]);

$container = $containerBuilder->build();

$routes = simpleDispatcher(function (RouteCollector $r) {
    global $BASE_URL;
    $r->post($BASE_URL . '/api/message', Main::class);
});

$middlewareQueue = [];

$middlewareQueue[] = new FastRoute($routes);
$middlewareQueue[] = new RequestHandler($container);

$requestHandler = new Relay($middlewareQueue);
$response = $requestHandler->handle(ServerRequestFactory::fromGlobals());
// $emitter = new SapiEmitter();
// return $emitter->emit($response);
