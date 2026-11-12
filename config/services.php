<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    // Підпис скінів Mojang (https://account.mineskin.org/keys) — без нього скін бачать лише гравці з модом
    'mineskin' => [
        'key' => env('MINESKIN_API_KEY'),
        'user_agent' => env('MINESKIN_USER_AGENT', 'PalermoCraft-Website/1.0'),
    ],

    // Discord OAuth2: https://discord.com/developers/applications → OAuth2 → Redirect: https://palermocraft.com/auth/discord/callback
    'discord' => [
        'client_id' => env('DISCORD_CLIENT_ID'),
        'client_secret' => env('DISCORD_CLIENT_SECRET'),
        'redirect' => env('DISCORD_REDIRECT_URI', env('APP_URL').'/auth/discord/callback'),
    ],

    // Telegram Login Widget: у @BotFather → /setdomain → palermocraft.com
    'telegram' => [
        'bot_username' => env('TELEGRAM_BOT_USERNAME'),
        'bot_token' => env('TELEGRAM_BOT_TOKEN'),
    ],

];
