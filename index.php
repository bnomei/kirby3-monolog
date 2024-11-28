<?php

use Bnomei\KirbyFormatter;
use Bnomei\Log;
use Kirby\Cms\Page;
use Kirby\Filesystem\Dir;
use Monolog\Handler\StreamHandler;
use Monolog\Level;
use Monolog\Logger;

@include_once __DIR__.'/vendor/autoload.php';

if (! function_exists('monolog')) {
    function monolog(string $channel = 'default'): ?Logger
    {
        return Log::singleton()->channel($channel);
    }
}

Kirby::plugin('bnomei/monolog', [
    'options' => [
        'channels' => [
            // set default logger as closure, aka lazy loaded
            'default' => option('bnomei.monolog.new-file-logger'),
            // 'example' => function() { return new \Monolog\Logger('example'); },
        ],
        'channels-extends' => [
            // load arrays of channels from other options
            // 'myplugin.name.channels',
        ],
        'dir' => function (): string {
            $dir = (string) kirby()->roots()->toArray()['logs'];
            if (! Dir::exists($dir)) {
                Dir::make($dir);
            }

            return $dir;
        },
        'filename' => function (?string $channel = null): string {
            return implode('-', array_filter([
                // $channel !== 'default' ? $channel : null,
                date('Y-m-d'),
                // kirby()->language()?->code(),
            ])).'.log';
        },
        'new-file-logger' => function (string $channel = 'default', ?string $file = null) {
            if (! $file) {
                $dir = option('bnomei.monolog.dir')(); // @phpstan-ignore-line
                $filename = option('bnomei.monolog.filename')($channel); // @phpstan-ignore-line
                $file = $dir.'/'.$filename;
            }

            $stream = new StreamHandler(strval($file), Level::Debug, true, null, true);
            $stream->setFormatter(new KirbyFormatter);

            return (new Logger($channel))->pushHandler($stream);
        },
    ],
    'pageMethods' => [
        'monolog' => function () {
            // create a unique channel for each page, each will have their own file
            return Log::singleton()->channel($this->monologChannel());
        },
        'monologChannel' => function (): string {
            // uuid or k2 hash
            /* @var Page $this*/
            return $this->uuid()?->id() ?? base_convert(sprintf('%u', crc32($this->uri())), 10, 36);
        },
    ],
]);
