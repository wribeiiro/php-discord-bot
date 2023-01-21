<?php

include __DIR__.'/vendor/autoload.php';

use Discord\Discord;
use Discord\Parts\Channel\Message;
use Discord\WebSockets\Intents;
use Discord\WebSockets\Event;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$discord = new Discord([
    'token' => $_ENV['DISCORD_APP_KEY'],
    'intents' => Intents::getDefaultIntents() | Intents::MESSAGE_CONTENT
]);

$discord->on('ready', function (Discord $discord) {
    echo "Bot is ready!", PHP_EOL;

    $discord->on('message', function (Message $message, Discord $discord) {
        if ($message->author->bot) {
            return;
        }

        if ($message->content == '?regras') {
            $message->reply("{$message->author->name} As regras do servidor são: " . PHP_EOL . 
                "1 - Não desrespeitar os membros; " . PHP_EOL . 
                "2 - Serão permitidos apenas assuntos relacionados a desenvolvimento;
            ");
        }

        if ($message->content == '?level') {
            $message->reply("Level ainda não definido!");
        }

        echo "{$message->author->username}: {$message->content}", PHP_EOL;
    });

    $discord->on(Event::GUILD_MEMBER_ADD, function (Member $member, Discord $discord) {
        if (!empty($member->guild->system_channel)) {
            $member->guild->system_channel->reply(
                "{$member->mention} acabou de entrar no {$member->guild->name}, welcome!"
            );
        }
    });
});

$discord->run();
