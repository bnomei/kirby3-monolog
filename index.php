<?php

@include_once __DIR__ . '/vendor/autoload.php';

Kirby::plugin('bnomei/monolog', [
    'options' => [
        'channels' => [
            // 'default => function() { return null; }, // disable default
            // 'example' => function() { return new \Monolog\Logger('example'); },
        ],
        'channels-extends' => [
            // 'myplugin.name.channels', // load arrays of channels from other options
        ],
        'dir' => function() {
            $dirs = [
                // https://github.com/getkirby/ideas/issues/493
                // zero-downtime deployments, try any of these
                realpath(kirby()->roots()->accounts() . '/../') . '/logs',
                realpath(kirby()->roots()->cache() . '/../') . '/logs',
                realpath(kirby()->roots()->sessions() . '/../') . '/logs',
                // kirby default
                kirby()->roots()->site() . '/logs',
            ];
            $dir = $dirs[0];
            foreach ($dirs as $existsDir) {
                if (is_dir($existsDir)) {
                    $dir = $existsDir;
                }
            }
            return $dir;
        },
        'hash' => function(\Kirby\Cms\Page $page) {
            $hash = $page->autoid();
            if (! $hash || $hash->isEmpty()) {
                // k2 hash
                $hash = base_convert(sprintf('%u', crc32($page->uri())), 10, 36);
            }
            return $hash;
        },
        'file' => function() {
            return option('bnomei.monolog.dir')() . '/' . date('Y-m-d') . '.log';
        },
        'default' => function (string $channel = 'default', string $file = null) {
            $file = $file ?? option('bnomei.monolog.file');
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

            $logger = new \Monolog\Logger($channel);
            $logger->pushHandler($stream);

            return $logger;
        },
    ],
    'pageMethods' => [
        'monolog' => function () {
            $hash = option('bnomei.monolog.hash')($this);
            $monolog = \Bnomei\Log::singleton();
            $channel = $monolog->channel($hash);
            if (! $channel) {
                $file = option('bnomei.monolog.dir')() . '/' . $hash . '.log';
                $channel = $monolog->setChannel($hash, option('bnomei.monolog.default')($hash, $file));
            }
            return $channel;
        },
    ],
]);

if (! class_exists('Bnomei\Log')) {
    require_once __DIR__ . '/classes/Log.php';
}

if (! function_exists('monolog')) {
    function monolog(string $channel = 'default')
    {
        return \Bnomei\Log::singleton()->channel($channel);
    }
}
