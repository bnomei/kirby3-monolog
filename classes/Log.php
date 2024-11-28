<?php

declare(strict_types=1);

namespace Bnomei;

use Closure;
use Kirby\Toolkit\A;
use Monolog\Logger;

class Log
{
    private array $channels;

    public function __construct()
    {
        $this->channels = [];

        // load channels from options
        foreach ((array) option('bnomei.monolog.channels', []) as $key => $value) {
            $this->channels[$key] = $value;
        }

        // load channels from other plugin options
        foreach ((array) option('bnomei.monolog.channels-extends', []) as $extend) {
            if (! is_string($extend)) {
                continue;
            }
            $extend = option($extend, []);
            $extend = $extend instanceof Closure ? $extend() : $extend;
            if (is_array($extend)) {
                foreach ($extend as $key => $value) {
                    $this->channels[$key] = $value;
                }
            }
        }
    }

    public function channel(string $name = 'default'): ?Logger
    {
        $value = A::get($this->channels, $name);

        // if missing set closure to default generator
        if (! $value) {
            $this->channels[$name] = option('bnomei.monolog.new-file-logger');
        }

        // if closure (aka lazy loading) then execute it and retrieve logger instance
        $value = A::get($this->channels, $name);
        if ($value instanceof Closure) {
            $value = $value($name);
            if ($value instanceof Logger) {
                $this->channels[$name] = $value;
            }
        }

        return A::get($this->channels, $name);
    }

    private static ?self $singleton = null;

    public static function singleton(): self
    {
        if (self::$singleton === null) {
            self::$singleton = new self;
        }

        return self::$singleton;
    }
}
