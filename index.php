<?php

@include_once __DIR__ . '/vendor/autoload.php';

Kirby::plugin('bnomei/monolog', [
    'options' => [
        'channels' => [
            // 'default => function() { return null; }, // disable default
            // 'example' => function() { return new \Monolog\Logger('example'); },
        ],
        'file' => function() {
            $dirs = [
                // https://github.com/getkirby/ideas/issues/493
                // zero-downtime deployments, try any of these
                realpath(kirby()->roots()->accounts() . '/../') . DIRECTORY_SEPARATOR . 'logs',
                realpath(kirby()->roots()->cache() . '/../') . DIRECTORY_SEPARATOR . 'logs',
                realpath(kirby()->roots()->sessions() . '/../') . DIRECTORY_SEPARATOR . 'logs',
                // kirby default
                kirby()->roots()->site() . DIRECTORY_SEPARATOR . 'logs',
            ];
            $dir = $dirs[0];
            foreach ($dirs as $existsDir) {
                if (is_dir($existsDir)) {
                    $dir = $existsDir;
                }
            }

            return $dir . DIRECTORY_SEPARATOR . date('Y-m-d') . '.log';
        },
        'default' => function () {
            $file = option('bnomei.monolog.file');
            if ($file && is_callable($file)) {
                $file = $file();
            }
            $stream = new \Monolog\Handler\StreamHandler(
                $file,
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
