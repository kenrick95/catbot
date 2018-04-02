<?php
declare(strict_types=1);
namespace Catbot;

use BotMan\BotMan\BotMan;
use BotMan\BotMan\BotManFactory;
use BotMan\BotMan\Drivers\DriverManager;


class Main
{
    private $botman;
    private $config = [
        'telegram' => [
            'token' => 'YOUR-TELEGRAM-TOKEN-HERE',
        ],
        'web' => [
            'matchingData' => [
                'driver' => 'web',
            ],
        ]
    ];

    public function __construct() {

        $this->botman = BotManFactory::create($this->config);

        DriverManager::loadDriver(\BotMan\Drivers\Telegram\TelegramDriver::class);

        $this->botman->hears('hello', function (BotMan $bot) {
            $bot->reply('Hello yourself.');
        });
    }

    public function start()
    {
        $this->botman->listen();
        echo 'Bot started!';
    }
}