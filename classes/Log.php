<?php

declare(strict_types=1);

namespace Bnomei;

use Kirby\Toolkit\A;
use Monolog\Logger;

final class Log
{
    /** @var array */
    private $channels;

    /** @var array */
    private $options;

    /**
     * Mailjet constructor.
     *
     * @param array $options
     */
    public function __construct(array $options = [])
    {
        $defaults = [
            'debug' => option('debug'),
            'channels' => array_merge([
                'default' => option('bnomei.monolog.default'),
            ], option('bnomei.monolog.channels', [])),
            'channels-extends' => option('bnomei.monolog.channels-extends'),
        ];
        $this->options = array_merge($defaults, $options);

        foreach ($this->options['channels-extends'] as $extend) {
            // NOTE: it is intended that channel override merged not other way around
            $this->options['channels'] = array_merge(option($extend, []), $this->options['channels']);
        }

        foreach ($this->options['channels'] as $key => $value) {
            $this->setChannel($key, $value);
        }
    }

    public function setChannel(string $name, $value): ?Logger
    {
        $this->channels[$name] = is_callable($value) ? $value() : $value;
        return $this->channels[$name];
    }

    /**
     * @return Logger
     */
    public function channel(string $name = 'default'): ?Logger
    {
        return A::get($this->channels, $name);
    }

    /** @var Log */
    private static $singleton;

    /**
     * @param array $options
     * @return Log
     */
    public static function singleton(array $options = [])
    {
        if (!self::$singleton) {
            self::$singleton = new self($options);
        }

        return self::$singleton;
    }
}
