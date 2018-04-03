<?php
declare(strict_types=1);
namespace Catbot;

use BotMan\BotMan\BotMan;
use BotMan\BotMan\BotManFactory;
use BotMan\BotMan\Drivers\DriverManager;
use BotMan\BotMan\Cache\SymfonyCache;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;

class Main
{
    private $botman;
    // private $response;
    private $config = [
        // 'telegram' => [
        //     'token' => 'YOUR-TELEGRAM-TOKEN-HERE',
        // ]
    ];

    public function __construct() {

        // $this->response = $response;

        // DriverManager::loadDriver(\BotMan\Drivers\Telegram\TelegramDriver::class);
        DriverManager::loadDriver(\BotMan\Drivers\Web\WebDriver::class);

        $adapter = new FilesystemAdapter();
        $this->botman = BotManFactory::create(
            $this->config,
            new SymfonyCache($adapter)
        );
        
        $this->botman->hears('hello', function (BotMan $bot) {
            $bot->reply('Meow!');
        });
        $this->botman->fallback(function (BotMan $bot) {
            $bot->reply('Meow');
        });
    }

    public function __invoke(): void
    {
        $this->botman->listen();
    }
}