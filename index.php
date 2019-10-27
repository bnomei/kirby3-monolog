<?php

@include_once __DIR__ . '/vendor/autoload.php';

Kirby::plugin('bnomei/monolog', [
    'options' => [
        'channels' => [
            // 'default => function() { return null; }, // disable default
            // 'example' => function() { return new \Monolog\Logger('example'); },
        ],
        'default' => function () {
            $stream = new \Monolog\Handler\StreamHandler(
                kirby()->roots()->site() . DIRECTORY_SEPARATOR . 'logs' . DIRECTORY_SEPARATOR . date('Y-m-d') . '.log',
                \Monolog\Logger::DEBUG,
                true,
                null,
                true
            );

            $stream->setFormatter(
                new \Bnomei\KirbyFormatter()
            );

            $logger = new \Monolog\Logger('default');
            $logger->pushHandler($stream);

            return $logger;
        },
    ],
]);

if (!class_exists('Bnomei\Log')) {
    require_once __DIR__ . '/classes/Log.php';
}

if (!function_exists('monolog')) {
    function monolog(string $channel = 'default')
    {
        return \Bnomei\Log::singleton()->channel($channel);
    }
}
