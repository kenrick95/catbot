<?php
declare(strict_types=1);

require_once __DIR__ . '/vendor/autoload.php';

use DI\ContainerBuilder;
use Catbot\Main;
use FastRoute\RouteCollector;
use Middlewares\FastRoute;
use Middlewares\RequestHandler;
use Relay\Relay;
use Laminas\Diactoros\ServerRequestFactory;
use Dotenv\Dotenv;
use function DI\create;
use function FastRoute\simpleDispatcher;
use function DI\get;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

$containerBuilder = new ContainerBuilder();
$containerBuilder->useAutowiring(false);
$containerBuilder->useAnnotations(false);
$containerBuilder->addDefinitions([
    Main::class => create(Main::class)->constructor(get('BotConfig')),
    'BotConfig' => [
        'telegram' => [
            'token' => getenv('TELEGRAM_API_KEY'),
        ],
        'facebook' => [
            'token' => getenv('FACEBOOK_TOKEN'),
            'app_secret' => getenv('FACEBOOK_APP_SECRET'),
            'verification'=> getenv('FACEBOOK_VERIFICATION'),
        ]
    ],
]);

$container = $containerBuilder->build();

$routes = simpleDispatcher(function (RouteCollector $r) {
    $r->post(getenv('BASE_URL') . '/api/message', Main::class);
    $r->get(getenv('BASE_URL') . '/api/message', Main::class);
});

$middlewareQueue = [];

$middlewareQueue[] = new FastRoute($routes);
$middlewareQueue[] = new RequestHandler($container);

$requestHandler = new Relay($middlewareQueue);
$response = $requestHandler->handle(ServerRequestFactory::fromGlobals());
