<?php

return [

    /*
    | Адреса ігрового сервера для статусу на головній сторінці (через api.mcsrvstat.us).
    */
    'server_address' => env('PALERMO_SERVER_ADDRESS', 'play.palermocraft.com'),

    /*
    | Версія Minecraft Java, з якою заходять на сервер (показується на сайті).
    */
    'game_version' => env('PALERMO_GAME_VERSION', '1.21.4'),

    /*
    | Спільнота: запрошення в Discord і канал/чат у Telegram. Порожні — кнопки не показуються.
    */
    'community' => [
        'discord' => env('PALERMO_DISCORD_INVITE'),
        'telegram' => env('PALERMO_TELEGRAM_URL'),
    ],

    /*
    | Спільний секрет між сайтом і плагінами. Плагін передає його в заголовку X-Palermo-Token.
    | Згенерувати: php -r "echo bin2hex(random_bytes(32));"
    */
    'game_api_token' => env('PALERMO_GAME_API_TOKEN'),

    /*
    | Необов'язково: вебхук плагіна palermo-skins для миттєвого оновлення скіна в грі.
    | Напр. http://IP_СЕРВЕРА:8085 — якщо хостинг не випускає на цей порт, скін оновиться при наступному вході.
    */
    'game_webhook_url' => env('PALERMO_GAME_WEBHOOK_URL'),

    'skins' => [
        // Нові скіни спершу перевіряє адмін
        'require_approval' => (bool) env('PALERMO_SKINS_REQUIRE_APPROVAL', true),
        'max_kb' => 100,
    ],

    'link_code_ttl_minutes' => 15,
];
